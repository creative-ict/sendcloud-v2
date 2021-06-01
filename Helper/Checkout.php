<?php

namespace SendCloud\SendCloudV2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use SendCloud\SendCloudV2\Logger\SendCloudLogger;

/**
 * Class Checkout
 * @package SendCloud\SendCloudV2\Helper
 */
class Checkout extends AbstractHelper
{
    private $sendCloudLogger;

    /**
     * Checkout constructor.
     * @param Context $context
     * @param SendCloudLogger $sendCloudLogger
     */
    public function __construct(Context $context, SendCloudLogger $sendCloudLogger)
    {
        parent::__construct($context);
        $this->sendCloudLogger = $sendCloudLogger;
    }

    /**
     * @return bool
     */
    public function checkForScriptUrl()
    {
        $isScriptUrlDefined = true;
        $scriptUrl = $this->scopeConfig->getValue('sendcloudv2/sendcloud/script_url', ScopeInterface::SCOPE_STORE);

        if ($scriptUrl == '' || $scriptUrl == null) {
            $this->sendCloudLogger->debug('The option service point is not active in Sendcloud');
            $isScriptUrlDefined = false;
        }

        return $isScriptUrlDefined;
    }

    /**
     * @return bool|mixed
     */
    public function checkIfModuleIsActive()
    {
        $isActive = $this->scopeConfig->getValue(
            'sendcloudv2/general/enable',
            ScopeInterface::SCOPE_STORE
        );

        return $isActive;
    }
}
