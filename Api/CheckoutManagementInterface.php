<?php

namespace SendCloud\SendCloudV2\Api;

interface CheckoutManagementInterface
{

    /**
     * PUT for checkout api
     * @api
     * @param array $param
     * @return string
     */
    public function putCheckout(array $param);


    /**
     * DELETE for checkout api
     * @api
     * @param string $param
     * @return string
     */
    public function deleteCheckout($param);
}
