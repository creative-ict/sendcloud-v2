<?php

namespace SendCloud\SendCloudV2\Block\Checkout;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloudV2\Helper\Checkoutconfiguration;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutConfig\CollectionFactory;

/**
 * Class Config
 * @package SendCloud\SendCloudV2\Block\Checkout
 */
class Config extends Template
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $checkoutConfigCollection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var Checkoutconfiguration
     */
    public $helper;

    /**
     * Config constructor.
     * @param Template\Context $context
     * @param CollectionFactory $checkoutConfigCollection
     * @param SerializerInterface $serializer
     * @param Checkoutconfiguration $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $checkoutConfigCollection,
        SerializerInterface $serializer,
        Checkoutconfiguration $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->checkoutConfigCollection = $checkoutConfigCollection;
        $this->serializer = $serializer;
        $this->helper = $helper;
    }

    /**
     * @return mixed
     */
    public function getScriptUrl()
    {
        return $this->_scopeConfig->getValue('sendcloudv2/sendcloud/script_url', ScopeInterface::SCOPE_STORE);
    }

    public function getIsActiveSCSkeleton()
    {
        return $this->_scopeConfig->getValue(
            'carriers/sendcloudv2skeleton/active',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getMethods()
    {
        return $this->helper->getBlockMethods();
    }


    public function getCheckoutConfig()
    {
        return $this->serializer->unserialize($this->checkoutConfigCollection->create()->getLastItem()->getConfigJson());
    }

    public function getLocale()
    {
        return $this->helper->getCurrentLocale();
    }
}
