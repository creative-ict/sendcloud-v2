<?php

namespace SendCloud\SendCloudV2\Model;

class CheckoutManagement
{
    /**
     * {@inheritdoc}
     */
    public function putCheckout($params)
    {
        return print_r($params, true);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteCheckout($param)
    {
        return 'api GET return the $param ' . $param;
    }

}
