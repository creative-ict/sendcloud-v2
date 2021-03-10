<?php

namespace SendCloud\SendCloudV2\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CheckoutConfig extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('sendcloud_checkout_config', 'entity_id');
    }

    public function getIdByConfigId($config_id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from('sendcloud_checkout_config', 'entity_id')->where('config_id = :config_id');

        $bind = [':config_id' => (string)$config_id];

        return $connection->fetchOne($select, $bind);
    }

}
