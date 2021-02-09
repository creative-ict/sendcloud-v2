<?php

namespace SendCloud\SendCloudV2\Api\Data;

interface SendcloudCheckoutPayloadInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    /*
     * sendcloud_checkout_payload.
     */
    const SENDCLOUD_CHECKOUT_PAYLOAD = 'sendcloud_checkout_payload';


    /**
     * Gets the Sendcloud Checkout_payload
     *
     * @return object|null SendcloudCheckoutPayload .
     */
    public function getSendcloudCheckoutPayload();

    /**
     * Sets checkout_payload
     *
     * @param array $sendcloudCheckoutPayload
     * @return \SendCloud\SendCloudV2\Api\Data\SendcloudCheckoutPayloadInterface
     */
    public function setSendcloudCheckoutPayload($sendcloudCheckoutPayload);
}
