<?php

namespace SendCloud\SendCloudV2\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutConfig\CollectionFactory;

class SendcloudSkeleton extends SendcloudAbstract implements CarrierInterface
{
    protected $_code = 'sendcloudv2skeleton';
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
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $checkoutConfigCollection;

    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param SerializerInterface $serializer
     * @param CollectionFactory $checkoutConfigCollection
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        SerializerInterface $serializer,
        CollectionFactory $checkoutConfigCollection,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->serializer = $serializer;
        $this->checkoutConfigCollection = $checkoutConfigCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $shippingPrice = $this->getConfigData('price');

        $result = $this->_rateResultFactory->create();

        if ($shippingPrice !== false) {
            $method = $this->_rateMethodFactory->create();

            $method->setCarrier($this->_code);


            if ($this->getConfigData('title')) {
                $method->setCarrierTitle($this->getConfigData('title'));
            } else {
                $method->setCarrierTitle($this->getExternalTitle());
            }

            $method->setMethod($this->_code);
            $method->setMethodTitle($this->getConfigData('name'));

            if ($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes()) {
                $shippingPrice = '0.00';
            }

            $method->setPrice($shippingPrice);
            $method->setCost($shippingPrice);

            $result->append($method);
        }

        return $result;
    }

    /**
     * getAllowedMethods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * @return array
     */
    private function getCheckoutConfig()
    {
        return $this->serializer->unserialize($this->checkoutConfigCollection->create()->getLastItem()->getConfigJson());
    }

    /**
     * @return mixed
     */
    private function getExternalTitle()
    {
        $configJson = $this->getCheckoutConfig();

        return $configJson['delivery_zones'][0]['delivery_methods'][0]['external_title'];
    }
}
