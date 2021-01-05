<?php

namespace SendCloud\SendCloudV2\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use SendCloud\SendCloudV2\Model\ResourceModel\Carrier\ServicepointrateFactory;

class Servicepointrate extends Value
{
    /**
     * @var ServicepointrateFactory
     */
    protected $_servicepointrateFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param ServicepointrateFactory $servicepointrateFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        ServicepointrateFactory $servicepointrateFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_servicepointrateFactory = $servicepointrateFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return Servicepointrate
     * @throws LocalizedException
     */
    public function afterSave()
    {
        /** @var \SendCloud\SendCloudV2\Model\ResourceModel\Carrier\Servicepointrate $servicepointRate */
        $servicepointRate = $this->_servicepointrateFactory->create();
        $servicepointRate->uploadAndImport($this);
        return parent::afterSave();
    }
}
