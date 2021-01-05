<?php

namespace SendCloud\SendCloudV2\Model\Config\Backend;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use SendCloud\SendCloudV2\Helper\Backend;


class Support extends Field
{
    /**
     * @var Configuration
     */
    public $backendHelper;

    /**
     * @param Context $context
     * @param Backend $backendHelper
     */
    public function __construct(
        Context $context,
        Backend $backendHelper
    ) {
        parent::__construct($context);
        $this->backendHelper = $backendHelper;
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
        if (!extension_loaded('soap')) {
            $integrationResult .= '<span style="color:red">SendCloud Integration is not activated!</span><br/>';
        }

        return $integrationResult;
    }
}
