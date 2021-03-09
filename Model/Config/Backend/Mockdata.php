<?php

namespace SendCloud\SendCloudV2\Model\Config\Backend;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use SendCloud\SendCloudV2\Helper\Backend;


class Mockdata extends Field
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
        $mockdata = false;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $mockModel = $objectManager->get('SendCloud\SendCloudV2\Model\Checkoutconfiguration');
        $mockdata = $mockModel->get();

        $html = '<div><p>Mockdata from the middleware in core_config:</p>';
        $html .='<textarea id="mockdata" name="mockdata" rows="50" cols="70">' . print_r($mockdata, true) . '</textarea>';
//        $html .='<pre style="max-width:100%">' . print_r($mockdata, true) . '</pre>';
        $html .= '</div>';

        return $html;
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