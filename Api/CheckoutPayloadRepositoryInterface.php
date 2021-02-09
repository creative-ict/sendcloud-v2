<?php

namespace SendCloud\SendCloudV2\Api;

use SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface;

interface CheckoutPayloadRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param CheckoutPayloadInterface $checkoutPayload
     * @return CheckoutPayloadInterface
     */
    public function save(CheckoutPayloadInterface $checkoutPayload);
}
