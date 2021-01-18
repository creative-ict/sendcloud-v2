<?php

namespace SendCloud\SendCloudV2\Block\Order;

use \Magento\Sales\Block\Order\Info as Original;

class Info extends Original
{
    /**
     * @var string
     */
    protected $_template = 'SendCloud_SendCloudV2::order/info.phtml';
}
