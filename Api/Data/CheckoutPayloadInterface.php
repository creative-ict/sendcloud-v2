<?php

namespace SendCloud\SendCloudV2\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface CheckoutPayloadInterface extends ExtensibleDataInterface
{
    const KEY_SHIPPING_PRODUCT = 'shipping_product';
    const KEY_NOMINATED_DAY = 'nominated_day_delivery';

    /**
     * @return \SendCloud\SendCloudV2\Api\Data\ShippingProductInterface
     */
    public function getShippingProduct();

    /**
     * @param \SendCloud\SendCloudV2\Api\Data\ShippingProductInterface $shippingProduct
     * @return mixed
     */
    public function setShippingProduct(\SendCloud\SendCloudV2\Api\Data\ShippingProductInterface $shippingProduct);

    /**
     * @return \SendCloud\SendCloudV2\Api\Data\NominatedDayDeliveryInterface
     */
    public function getNominatedDayDelivery();

    /**
     * @param \SendCloud\SendCloudV2\Api\Data\NominatedDayDeliveryInterface $nominatedDay
     * @return mixed
     */
    public function setNominatedDayDelivery(\SendCloud\SendCloudV2\Api\Data\NominatedDayDeliveryInterface $nominatedDay);

    /**
     * @return \SendCloud\SendCloudV2\Api\Data\CheckoutPayloadExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \SendCloud\SendCloudV2\Api\Data\CheckoutPayloadExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\SendCloud\SendCloudV2\Api\Data\CheckoutPayloadExtensionInterface $extensionAttributes);
}
