<?php

namespace SendCloud\SendCloudV2\Model\Carrier;

use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;

abstract class SendcloudAbstract extends \Magento\Shipping\Model\Carrier\AbstractCarrier
    implements AbstractCarrierInterface
{

    /**
     * @return string
     */
    protected function test()
    {
        return 'test';
    }

}
