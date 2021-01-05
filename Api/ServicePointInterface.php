<?php

namespace SendCloud\SendCloudV2\Api;

/**
 * Interface ServicePointInterface
 * @package SendCloud\SendCloudV2\Api
 */
interface ServicePointInterface
{
    /**
     * @api
     * @param string $script_url
     * @return mixed[]
     */
    public function activate($script_url);

    /**
     * @return mixed
     */
    public function deactivate();

    /**
     * @param boolean $activate
     * @return mixed
     */
    public function shippingEmail($activate);
}
