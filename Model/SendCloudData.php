<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface;
use SendCloud\SendCloudV2\Api\Data\SendCloudDataExtensionInterface;
use SendCloud\SendCloudV2\Api\Data\SendCloudDataInterface;

class SendCloudData extends AbstractExtensibleModel implements SendCloudDataInterface
{
    /**
     * @param CheckoutPayloadInterface $checkoutPayload
     * @return SendCloudData
     */
    public function setCheckoutPayload(CheckoutPayloadInterface $checkoutPayload)
    {
        return $this->setData('checkout_payload', $checkoutPayload);
    }

    /**
     * @return CheckoutPayloadInterface
     */
    public function getCheckoutPayload()
    {
        return $this->_getData('checkout_payload');
    }

    /**
     * @param SendCloudDataExtensionInterface $extensionAttributes
     * @return SendCloudData
     */
    public function setExtensionAttributes(SendCloudDataExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * @return SendCloudDataExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }
}
