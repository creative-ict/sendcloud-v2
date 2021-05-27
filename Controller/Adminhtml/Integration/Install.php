<?php

namespace SendCloud\SendCloudV2\Controller\Adminhtml\Integration;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;

class Install extends Action
{
    /**
     * @var ConfigBasedIntegrationManager
     */
    protected $integrationManager;

    public function __construct(
        Context $context,
        ConfigBasedIntegrationManager $integrationManager
    )
    {
        parent::__construct($context);
        $this->integrationManager = $integrationManager;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        try{
            $this->integrationManager->processIntegrationConfig(['SendCloud']);
            $this->messageManager->addSuccessMessage(__('Sendcloud Integration installed successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(__('Sendcloud Integration fails.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('adminhtml/system_config/edit/', ['section' => 'sendcloudv2']);
        return $resultRedirect;
    }
}
