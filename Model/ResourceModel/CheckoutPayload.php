<?php

namespace SendCloud\SendCloudV2\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CheckoutPayload extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sendcloud_checkout_payload', 'entity_id');
    }

    public function getIdByQuoteId($quoteId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from('sendcloud_checkout_payload', 'entity_id')->where('quote_id = :quote_id');

        $bind = [':quote_id' => (int)$quoteId];

        return $connection->fetchOne($select, $bind);
    }
}
