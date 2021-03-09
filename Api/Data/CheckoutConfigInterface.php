<?php

namespace SendCloud\SendCloudV2\Api\Data;

use SendCloud\SendCloudV2\Model\CheckoutConfig;

interface CheckoutConfigInterface
{
    /**
     * @param string $json
     * @return CheckoutConfig
     */
    public function setConfigJson($json);

    /**
     * @return string[]
     */
    public function getConfigJson();

    /**
     * @param string $id
     * @return mixed|CheckoutConfig
     */
    public function setConfigId($id);

    /**
     * @return string
     */
    public function getConfigId();
}
