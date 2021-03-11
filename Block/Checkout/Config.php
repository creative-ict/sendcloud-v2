<?php

namespace SendCloud\SendCloudV2\Block\Checkout;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
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

    public function __construct(
        Template\Context $context,
        CollectionFactory $checkoutConfigCollection,
        SerializerInterface $serializer,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->checkoutConfigCollection = $checkoutConfigCollection;
        $this->serializer = $serializer;
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

    public function getCheckoutConfig()
    {
        return $this->serializer->unserialize($this->checkoutConfigCollection->create()->getLastItem()->getConfigJson());
    }
}
