<?php

namespace SendCloud\SendCloudV2\Model\ResourceModel\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloudV2\Logger\SendCloudLogger;
use SendCloud\SendCloudV2\Model\Carrier\SendcloudServicepoint;
use SendCloud\SendCloudV2\Model\ResourceModel\Carrier\Servicepointrate\Import;
use SendCloud\SendCloudV2\Model\ResourceModel\Carrier\Servicepointrate\RateQuery;
use SendCloud\SendCloudV2\Model\ResourceModel\Carrier\Servicepointrate\RateQueryFactory;
use Magento\Framework\App\RequestInterface;


/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @api
 * @since 100.0.2
 */
class Servicepointrate extends AbstractDb
{


    /**
     * @var string
     */
    protected $_fullCarrierName = 'sendcloudv2servicepoint';

    const XML_PATH_SENDCLOUDV2 = 'groups/sendcloudv2servicepoint/fields/sen_condition_name/';
    const XML_PATH_SENDCLOUDV2_INHERIT = self::XML_PATH_SENDCLOUDV2 . 'inherit';
    const XML_PATH_SENDCLOUDV2_VALUE = self::XML_PATH_SENDCLOUDV2 . 'value';
    const ADMIN_PATH_SENDCLOUDV2_CARRIER = 'carriers/sendcloudv2servicepoint/sen_condition_name';

    /**
     * Import Service point rates website ID
     *
     * @var int
     */
    protected $_importWebsiteId = 0;

    /**
     * Errors in import process
     *
     * @var array
     */
    protected $_importErrors = [];

    /**
     * Count of imported Service point rates
     *
     * @var int
     */
    protected $_importedRows = 0;

    /**
     * Array of unique table rate keys to protect from duplicates
     *
     * @var array
     */
    protected $_importUniqueHash = [];

    /**
     * Array of countries keyed by iso2 code
     *
     * @var array
     */
    protected $_importIso2Countries;

    /**
     * Array of countries keyed by iso3 code
     *
     * @var array
     */
    protected $_importIso3Countries;

    /**
     * Associative array of countries and regions
     * [country_id][region_code] = region_id
     *
     * @var array
     */
    protected $_importRegions;

    /**
     * Import Table Rate condition name
     *
     * @var string
     */
    protected $_importConditionName;

    /**
     * Array of condition full names
     *
     * @var array
     */
    protected $_conditionFullNames = [];

    /**
     * @var ScopeConfigInterface
     * @since 100.1.0
     */
    protected $coreConfig;

    /**
     * @var SendCloudLogger
     */
    protected $logger;

    /**
     * @var StoreManagerInterface
     * @since 100.1.0
     */
    protected $storeManager;

    /**
     * @var Servicepointrate
     * @since 100.1.0
     */
    protected $carrierServicepointrate;

    /**
     * Filesystem instance
     *
     * @var Filesystem
     * @since 100.1.0
     */
    protected $filesystem;

    /**
     * @var Import
     */
    private $import;

    /**
     * @var RateQueryFactory
     */
    private $rateQueryFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Servicepointrate constructor.
     * @param Context $context
     * @param SendCloudLogger $logger
     * @param ScopeConfigInterface $coreConfig
     * @param StoreManagerInterface $storeManager
     * @param SendcloudServicepoint $carrierServicepointrate
     * @param Filesystem $filesystem
     * @param Import $import
     * @param RateQueryFactory $rateQueryFactory
     * @param RequestInterface $request
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        SendCloudLogger $logger,
        ScopeConfigInterface $coreConfig,
        StoreManagerInterface $storeManager,
        SendcloudServicepoint $carrierServicepointrate,
        Filesystem $filesystem,
        Import $import,
        RateQueryFactory $rateQueryFactory,
        RequestInterface $request,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->coreConfig = $coreConfig;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->carrierServicepointrate = $carrierServicepointrate;
        $this->filesystem = $filesystem;
        $this->import = $import;
        $this->rateQueryFactory = $rateQueryFactory;
        $this->request = $request;
    }

    /**
     * Define main table and id field name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sendcloudv2_servicepointrate', 'pk');
    }

    /**
     * Return table rate array or false by rate request
     *
     * @param RateRequest $request
     * @return mixed
     * @throws LocalizedException
     */
    public function getRate(RateRequest $request)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from($this->getMainTable());
        /** @var RateQuery $rateQuery */
        $rateQuery = $this->rateQueryFactory->create(['request' => $request]);

        $rateQuery->prepareSelect($select);
        $bindings = $rateQuery->getBindings();

        $result = $connection->fetchRow($select, $bindings);
        // Normalize destination zip code
        if ($result && $result['dest_zip'] == '*') {
            $result['dest_zip'] = '';
        }

        return $result;
    }

    /**
     * @param array $condition
     * @return $this
     * @throws LocalizedException
     */
    private function deleteByCondition(array $condition)
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        $connection->delete($this->getMainTable(), $condition);
        $connection->commit();
        return $this;
    }

    /**
     * @param array $fields
     * @param array $values
     * @throws LocalizedException
     * @return void
     */
    private function importData(array $fields, array $values)
    {

        $connection = $this->getConnection();
        $connection->beginTransaction();

        try {
            if (count($fields) && count($values)) {

                $this->getConnection()->insertArray($this->getMainTable(), $fields, $values);
                $this->_importedRows += count($values);
            }
        } catch (LocalizedException $e) {
            $connection->rollBack();
            throw new LocalizedException(__('Unable to import data'), $e);
        } catch (\Exception $e) {
            $connection->rollBack();
            $this->logger->critical($e);
            throw new LocalizedException(
                __('Something went wrong while importing %1 rates.', $this->_conditionFullNames)
            );
        }
        $connection->commit();
    }

    /**
     * Upload Sendcloud Servicepoint rate file and import data from it
     *
     * @param DataObject $object
     * @return Servicepointrate
     * @throws LocalizedException
     * @todo: this method should be refactored as soon as updated design will be provided
     * @see https://wiki.corp.x.com/display/MCOMS/Magento+Filesystem+Decisions
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function uploadAndImport(DataObject $object)
    {
        /**
         * @var \Magento\Framework\App\Config\Value $object
         */
        $files = $this->request->getFiles()->toArray();
        if (!isset($files['groups']['sendcloudv2servicepoint']['fields']['sen_import']['value'])) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while importing Sendcloud Servicepoint rates.')
            );
        }
        if(empty($files['groups']['sendcloudv2servicepoint']['fields']['sen_import']['value']['tmp_name'])){
            return false;
        }
        $filePath = $files['groups']['sendcloudv2servicepoint']['fields']['sen_import']['value']['tmp_name'];

        $websiteId = $this->storeManager->getWebsite($object->getScopeId())->getId();
        $conditionName = $this->getSenConditionName($object);

        $file = $this->getCsvFile($filePath);
        try {
            // delete old data by website and condition name
            $condition = [
                'website_id = ?' => $websiteId,
                'sen_condition_name = ?' => $conditionName,
            ];
            $this->deleteByCondition($condition);

            $columns = $this->import->getColumns();
            $conditionFullName = $this->_getConditionFullName($conditionName);
            foreach ($this->import->getData($file, $websiteId, $conditionName, $conditionFullName) as $bunch) {
                $this->importData($columns, $bunch);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new LocalizedException(
                __('Something went wrong while importing %1 rates.', print_r($e->getMessage(), true))
            );
        } finally {
            $file->close();
        }

        if ($this->import->hasErrors()) {
            $error = __(
                'We couldn\'t import this file because of these errors: %1',
                implode(" \n", $this->import->getErrors())
            );
            throw new LocalizedException($error);
        }
    }

    /**
     * @param DataObject $object
     * @return mixed|string
     * @since 100.1.0
     */
    public function getSenConditionName(DataObject $object)
    {
        if ($object->getData(self::XML_PATH_SENDCLOUDV2_INHERIT) == '1') {
            $conditionName = (string)$this->coreConfig->getValue(self::ADMIN_PATH_SENDCLOUDV2_CARRIER, 'default');
        } else {
            $conditionName = $object->getData(self::XML_PATH_SENDCLOUDV2_VALUE);
        }
        return $conditionName;
    }

    /**
     * @param string $filePath
     * @return Filesystem\File\ReadInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function getCsvFile($filePath)
    {
        $pathInfo = pathinfo($filePath);
        $dirName = isset($pathInfo['dirname']) ? $pathInfo['dirname'] : '';
        $fileName = isset($pathInfo['basename']) ? $pathInfo['basename'] : '';

        $directoryRead = $this->filesystem->getDirectoryReadByPath($dirName);

        return $directoryRead->openFile($fileName);
    }


    /**
     * Return import condition full name by condition name code
     *
     * @param string $conditionName
     * @return string
     * @throws LocalizedException
     */
    protected function _getConditionFullName($conditionName)
    {
        if (!isset($this->_conditionFullNames[$conditionName])) {
            $name = $this->carrierServicepointrate->getCode('sen_condition_name_short', $conditionName);
            $this->_conditionFullNames[$conditionName] = $name;
        }

        return $this->_conditionFullNames[$conditionName];
    }

    /**
     * Save import data batch
     *
     * @param array $data
     * @return $this
     * @throws LocalizedException
     */
    protected function _saveImportData(array $data)
    {
        if (!empty($data)) {
            $columns = [
                'website_id',
                'dest_country_id',
                'dest_region_id',
                'dest_zip',
                'sen_condition_name',
                'condition_value',
                'price',
            ];
            $this->getConnection()->insertArray($this->getMainTable(), $columns, $data);
            $this->_importedRows += count($data);
        }

        return $this;
    }
}
