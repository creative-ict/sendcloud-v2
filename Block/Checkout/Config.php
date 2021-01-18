<?php

namespace SendCloud\SendCloudV2\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package SendCloud\SendCloudV2\Block\Checkout
 */
class Config extends Template
{
    /**
     * @return mixed
     */
    public function getScriptUrl()
    {
        return $this->_scopeConfig->getValue('sendcloudv2/sendcloud/script_url', ScopeInterface::SCOPE_STORE);
    }
}
