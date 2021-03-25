<?php

namespace SendCloud\SendCloudV2\Api;

interface CheckoutManagementInterface
{

    /**
     * @param mixed $checkout_configuration
     * @return \SendCloud\SendCloudV2\Api\Data\CheckoutConfigInterface
     */
    public function putCheckout($checkout_configuration);


    /**
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    public function deleteCheckout(string $id): bool;
}
