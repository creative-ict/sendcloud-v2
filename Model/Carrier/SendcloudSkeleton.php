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
use SendCloud\SendCloudV2\Helper\Checkoutconfiguration;

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
    private $serializer;

    /**
     * @var CollectionFactory
     */
    private $checkoutConfigCollection;


    /**
     * @var Checkoutconfiguration
     */
    private $checkouConfigurationHelper;

    /**
     * SendcloudSkeleton constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param SerializerInterface $serializer
     * @param CollectionFactory $checkoutConfigCollection
     * @param Checkoutconfiguration $checkouConfigurationHelper
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
        Checkoutconfiguration $checkouConfigurationHelper,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->serializer = $serializer;
        $this->checkoutConfigCollection = $checkoutConfigCollection;
        $this->checkouConfigurationHelper = $checkouConfigurationHelper;
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

        if(in_array($request->getDestCountryId(), $this->checkouConfigurationHelper->getZones())) {

            $shippingPrice = $this->getConfigData('price');

            $methods = $this->checkouConfigurationHelper->getMethods();
            foreach ($methods[$request->getDestCountryId()] as $value)
            {
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
        return [$this->_code => $this->getConfigData('name')];
    }
}
