<?php

namespace SendCloud\SendCloudV2\Controller\Adminhtml\Integration;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Integration\Api\IntegrationServiceInterface;

class Remove extends Action
{

    /**
     * @var IntegrationServiceInterface
     */
    protected $integrationService;

    public function __construct(
        Context $context,
        IntegrationServiceInterface $integrationService
    )
    {
        parent::__construct($context);
        $this->integrationService = $integrationService;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('adminhtml/system_config/edit/', ['section' => 'sendcloudv2']);

        $integration = $this->integrationService->findByName('SendCloud');
        if(!$integration->getId())
        {
            $this->messageManager->addExceptionMessage(__('Integration SendCloud not found'));
            return $resultRedirect;
        }

        try{
            $integration->delete();
            $this->messageManager->addSuccessMessage(__('SendCloud Integration removed.'));
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage(__('Remove SendCloud Integration fails. ' . $e->getMessage()));
        }

        return $resultRedirect;
    }
}
