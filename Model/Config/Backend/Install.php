<?php

namespace SendCloud\SendCloudV2\Model\Config\Backend;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Integration\Api\IntegrationServiceInterface;

class Install extends Field
{
    /**
     * Install constructor.
     * @param Context $context
     * @param IntegrationServiceInterface $integrationService
     */
    public function __construct(
        Context $context,
        IntegrationServiceInterface $integrationService
    )
    {
        parent::__construct($context);
        $this->integrationService = $integrationService;
    }

    /**
     * @var IntegrationServiceInterface
     */
    protected $integrationService;

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        /** @var Button $buttonBlock  */
        $buttonBlock = $this->getForm()->getLayout()->createBlock(Button::class);
        $integration = $this->integrationService->findByName('SendCloud');

        $url = $this->getUrl('setup_integration/integration/install');
        $label = __('Install Integration');
        if($integration->getId()){
            $url = $this->getUrl('setup_integration/integration/remove');
            $label = __('Remove Integration');
        }

        $data = [
            'label' => $label,
            'onclick' => "setLocation('" . $url . "')",
        ];


        return $buttonBlock->setData($data)->toHtml();
    }
}
