<?php

namespace SendCloud\SendCloudV2\Model\Config\Backend;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use SendCloud\SendCloudV2\Helper\Backend;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutConfig\CollectionFactory;


class Mockdata extends Field
{
    /**
     * @var Configuration
     */
    public $backendHelper;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $checkoutConfigCollection;

    /**
     * @param Context $context
     * @param Backend $backendHelper
     * @param CollectionFactory $checkoutConfigCollection
     */
    public function __construct(
        Context $context,
        Backend $backendHelper,
        CollectionFactory $checkoutConfigCollection
    ) {
        parent::__construct($context);
        $this->backendHelper = $backendHelper;
        $this->checkoutConfigCollection = $checkoutConfigCollection;
    }

    /**
     * Retrieve element HTML markup
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $mockdata = $this->checkoutConfigCollection->create()->getLastItem()->getConfigJson();

        $html = '<div><p>Checkout Configuration JSON array</p>';
        $html .='<textarea id="mockdata" name="mockdata" rows="50" cols="70">' . print_r($mockdata, true) . '</textarea>';
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
