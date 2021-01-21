<?php

namespace SendCloud\SendCloudV2\Model;

use Exception;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Integration\Api\IntegrationServiceInterface;
use SendCloud\SendCloudV2\Api\PluginInterface;
use SendCloud\SendCloudV2\Logger\SendCloudLogger;

class Plugin implements PluginInterface
{
    /** @var WriterInterface */
    private $writer;

    /** @var SendCloudLogger */
    private $logger;

    /** @var TypeListInterface */
    private $cache;

    /**
     * Integration service
     *
     * @var IntegrationServiceInterface
     */
    protected $integrationService;

    public function __construct(
        WriterInterface $writer,
        SendCloudLogger $logger,
        TypeListInterface $cache,
        IntegrationServiceInterface $integrationService
    )
    {
        $this->writer = $writer;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->integrationService = $integrationService;
    }

    public function uninstall()
    {
        try {
            $this->writer->save('carriers/sendcloudv2/active', 0, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->writer->save('carriers/sendcloudskeleton/active', 0, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->writer->save('carriers/sendcloudservicepoint/active', 0, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->writer->save('sendcloud/sendcloudv2/script_url', "", ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->writer->save('sendcloudv2/general/enable', 0, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            $this->cache->cleanType('config');

            $integration = $this->integrationService->findByName('SendCloud');
            if ($integration->getId()) {
                $this->integrationService->delete($integration->getId());
            }
        } catch (Exception $ex) {
            $this->logger->debug($ex->getMessage());
            return ['message' => ['error' => 'Uninstall of this module has failed']];
        }

        return ['message' => ['success' => 'Uninstall of this module succeeded']];
    }
}
