<?php

namespace SendCloud\SendCloudV2\Api;

/**
 * Interface PluginInterface
 * @package SendCloud\SendCloudV2\Api
 */
interface PluginInterface
{
    /**
     * @return mixed[]
     * @api
     */
    public function uninstall();
}
