<?php

namespace SendCloud\SendCloudV2\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;

class InstallData implements InstallDataInterface
{
    /**
     * @var ConfigBasedIntegrationManager
     */
    private $integrationManager;

    /**
     * InstallData constructor.
     * @param ConfigBasedIntegrationManager $integrationManager
     */
    public function __construct(ConfigBasedIntegrationManager $integrationManager)
    {
        $this->integrationManager = $integrationManager;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        //TODO: processIntegrationConfig deprecation fix need to implemented
        $this->integrationManager->processIntegrationConfig(['SendCloud']);
    }
}
