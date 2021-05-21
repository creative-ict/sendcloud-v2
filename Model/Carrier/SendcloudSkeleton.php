<?php

namespace SendCloud\SendCloudV2\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http as Request;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use SendCloud\SendCloudV2\Helper\Checkoutconfiguration;

class SendcloudSkeleton extends SendcloudAbstract implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'sendcloudv2skeleton';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * @var MethodFactory
     */
    protected $_rateMethodFactory;

    /**
     * @var Checkoutconfiguration
     */
    private $checkoutConfigurationHelper;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * SendcloudSkeleton constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param Checkoutconfiguration $checkoutConfigurationHelper
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        Checkoutconfiguration $checkoutConfigurationHelper,
        Request $request,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->checkoutConfigurationHelper = $checkoutConfigurationHelper;
        $this->request = $request;
        $this->getDeliveryZoneMethods();
    }

    /**
     * {@inheritdoc}
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = $this->_rateResultFactory->create();

        if (in_array($request->getDestCountryId(), $this->checkoutConfigurationHelper->getZones())) {
            $shippingPrice = $this->getConfigData('price');

            $methods = $this->checkoutConfigurationHelper->getMethods();
            foreach ($methods[$request->getDestCountryId()] as $value) {
                $method = $this->_rateMethodFactory->create();
                $method->setCarrier($this->_code);

                $method->setCarrierTitle($value->getCarrierName());

                $method->setMethod($value->getId());
                $method->setMethodTitle($value->getTitle());

                $method->setPrice($shippingPrice);
                $method->setCost($shippingPrice);

                $result->append($method);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        $methods = $this->checkoutConfigurationHelper->getMethods();
        $countryId = $this->getCountryId();
        $methodsArray = [];

        if (isset($methods[$countryId])) {
            foreach ($methods[$countryId] as $method) {
                $methodsArray[$method->getId()] = $this->getMethodTitle($method);
            }
        } else {
            $methods = $this->checkoutConfigurationHelper->getAllMethods();
            foreach ($methods as $method) {
                $methodsArray[$method->getId()] = $this->getMethodTitle($method);
            }
        }

        return $methodsArray;
    }

    /**
     * @return mixed
     */
    private function getCountryId()
    {
        $storeId = $this->getCurrentStoreId();

        return $this->_scopeConfig->getValue('general/country/default', ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @return int
     */
    private function getCurrentStoreId()
    {
        $storeId = 0;

        if ($this->request->getParam('store') !== null) {
            $storeId = $this->request->getParam('store');
        }

        return $storeId;
    }

    /**
     * @param $method
     * @return string
     */
    private function getMethodTitle($method)
    {
        return $method->getCarrierName() . ' ' . $method->getTitle();
    }
}
