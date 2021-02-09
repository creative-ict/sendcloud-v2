<?php

namespace SendCloud\SendCloudV2\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface SendCloudDataInterface extends ExtensibleDataInterface
{
    const KEY_SHIPPING_PRODUCT = 'shipping_product';
    const KEY_NOMINATED_DAY = 'nominated_day_delivery';

    /**
     * @param \SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface $checkoutPayload
     * @return $this
     */
    public function setCheckoutPayload(\SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface $checkoutPayload);

    /**
     * @return \SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface
     */
    public function getCheckoutPayload();

    /**
     * @param \SendCloud\SendCloudV2\Api\Data\SendCloudDataExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\SendCloud\SendCloudV2\Api\Data\SendCloudDataExtensionInterface $extensionAttributes);

    /**
     * @return \SendCloud\SendCloudV2\Api\Data\SendCloudDataExtensionInterface|null
     */
    public function getExtensionAttributes();
}
