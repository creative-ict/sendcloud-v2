<?php
namespace SendCloud\SendCloudV2\Logger;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

/**
 * Class SendCloudHandler
 * @package SendCloud\SendCloudV2\Logger
 */
class SendCloudHandler extends Base
{
    protected $loggerType = Logger::DEBUG;
    protected $fileName = '/var/log/sendcloudv2_exception.log';
}
