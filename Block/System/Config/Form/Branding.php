<?php

namespace SendCloud\SendCloudV2\Block\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Branding extends Field
{
    const SENDCLOUD_URL = 'https://www.sendcloud.com';
    const CREATIVECT_URL = 'https://www.creative-ct.nl';

    protected $_template = 'SendCloud_SendCloudV2::system/config/branding.phtml';

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->addClass('creative-ct');

        return $this->toHtml();
    }

    public function getSendCloudLogo()
    {
        return $this->getViewFileUrl('SendCloud_SendCloudV2::images/sendcloud-logo.svg');
    }

    public function getCreativeCTLogo()
    {
        return $this->getViewFileUrl('SendCloud_SendCloudV2::images/creative-ct-logo.svg');
    }

    public function getCreativeCTUrl()
    {
        return self::CREATIVECT_URL;
    }

    public function getSendCloudUrl()
    {
        return self::SENDCLOUD_URL;
    }
}
