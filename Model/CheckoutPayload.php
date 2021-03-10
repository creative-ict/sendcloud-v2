<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use SendCloud\SendCloudV2\Api\Data\CheckoutPayloadExtensionInterface;
use SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface;
use SendCloud\SendCloudV2\Api\Data\ShippingProductInterface;
use SendCloud\SendCloudV2\Api\Data\NominatedDayDeliveryInterface;

class CheckoutPayload extends AbstractExtensibleModel implements CheckoutPayloadInterface
{
    protected function _construct()
    {
        $this->_init(\SendCloud\SendCloudV2\Model\ResourceModel\CheckoutPayload::class);
    }

    public function getSenderAddressId()
    {
        return $this->getData(self::KEY_SENDER_ADDRESS_ID);
    }

    public function setSenderAddressId($id)
    {
        return $this->setData(self::KEY_SENDER_ADDRESS_ID, $id);
    }

    public function getShippingProduct()
    {
        return $this->getData(self::KEY_SHIPPING_PRODUCT);
    }

    public function setShippingProduct(ShippingProductInterface $shippingProduct)
    {
        return $this->setData(self::KEY_SHIPPING_PRODUCT, $shippingProduct);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        CheckoutPayloadExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    public function getNominatedDayDelivery()
    {
        return $this->_getData(self::KEY_NOMINATED_DAY);
    }

    public function setNominatedDayDelivery(NominatedDayDeliveryInterface $nominatedDay)
    {
        return $this->setData(self::KEY_NOMINATED_DAY, $nominatedDay);
    }
}
