<?php

namespace SendCloud\SendCloudV2\Model\Config\Backend;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Integration\Api\IntegrationServiceInterface;
use SendCloud\SendCloudV2\Helper\Backend;


class Support extends Field
{
    /**
     * @var Configuration
     */
    public $backendHelper;

    /**
     * @var IntegrationServiceInterface
     */
    protected $integrationService;

    /**
     * @param Context $context
     * @param Backend $backendHelper
     * @param IntegrationServiceInterface $integrationService
     */
    public function __construct(
        Context $context,
        Backend $backendHelper,
        IntegrationServiceInterface $integrationService
    ) {
        parent::__construct($context);
        $this->backendHelper = $backendHelper;
        $this->integrationService = $integrationService;
    }

    /**
     * Retrieve element HTML markup
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $integrationUrl = $this->getUrl('adminhtml/integration');
        return '<div>'.$this->getModuleVersion().' |
        <a href="'.$integrationUrl.'">'.__('Check Integration').'</a>
        </div>'.$this->getCheckIntegration();
    }

    protected function getModuleVersion()
    {
        return $this->backendHelper->getModuleVersion();
    }

    /**
     * @return string
     */
    protected function getCheckIntegration()
    {
        $integrationResult = '<br/>';
        $integration = $this->integrationService->findByName('SendCloud');
        if (!$integration->getId()) {
            $integrationResult .= '<span style="color:red">SendCloud Integration is not installed!</span><br/>';
        }

        return $integrationResult;
    }
}
