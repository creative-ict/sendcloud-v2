<?php

namespace SendCloud\SendCloudV2\Setup\Patch\Data;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddCheckoutPayloadAttribute implements DataPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    /**
     * AddCheckoutPayloadAttribute constructor.
     * @param SchemaSetupInterface $schemaSetup
     */
    public function __construct(SchemaSetupInterface $schemaSetup)
    {
        $this->schemaSetup = $schemaSetup;
    }

    /**
     * @return AddCheckoutPayloadAttribute|void
     */
    public function apply()
    {
        $setup = $this->schemaSetup->startSetup();

        $this->addCheckoutPayloadColumn($setup, 'sales_order');
        $this->addCheckoutPayloadColumn($setup, 'sales_order_grid');
        $this->addCheckoutPayloadColumn($setup, 'quote');

        $setup->endSetup();
    }

    /**
     * @return array|string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @param $setup SchemaSetupInterface
     * @param $tableName
     * @return SchemaSetupInterface
     */
    private function addCheckoutPayloadColumn($setup, $tableName)
    {
        $connection = $setup->getConnection();
        $connection->addColumn(
            $setup->getTable($tableName),
            'sendcloud_checkout_payload',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'SendCloud Checkout Payload'
            ]
        );

        return $setup;
    }
}
