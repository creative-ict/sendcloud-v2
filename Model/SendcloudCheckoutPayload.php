<?php

namespace SendCloud\SendCloudV2\Model;

use SendCloud\SendCloudV2\Api\Data\SendcloudCheckoutPayloadInterface;

class SendcloudCheckoutPayload implements SendcloudCheckoutPayloadInterface
{

    private $sendcloudCheckoutPayload;

    public function getSendcloudCheckoutPayload()
    {
        return $this->sendcloudCheckoutPayload;
    }

    /**
     * Sets checkout_payload
     *
     * @param array|string $sendcloudCheckoutPayload
     * @return SendcloudCheckoutPayloadInterface
     */
    public function setSendcloudCheckoutPayload($sendcloudCheckoutPayload)
    {
        $this->sendcloudCheckoutPayload = json_decode($sendcloudCheckoutPayload);
    }
}
