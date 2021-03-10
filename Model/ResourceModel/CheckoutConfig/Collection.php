<?php

namespace SendCloud\SendCloudV2\Model\ResourceModel\CheckoutConfig;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'config_id';


    protected function _construct()
    {
        $this->_init('SendCloud\SendCloudV2\Model\CheckoutConfig', 'SendCloud\SendCloudV2\Model\ResourceModel\CheckoutConfig');
    }
}
