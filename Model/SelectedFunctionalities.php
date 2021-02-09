<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use SendCloud\SendCloudV2\Api\Data\SelectedFunctionalitiesInterface;

class SelectedFunctionalities extends AbstractExtensibleModel implements SelectedFunctionalitiesInterface
{
    /**
     * @param boolean $value
     * @return $this
     */
    public function setSignature($value)
    {
        return $this->setData('signature', $value);
    }

    /**
     * @return boolean
     */
    public function getSignature()
    {
        return $this->getData('signature');
    }

    public function setExtensionAttributes(\SendCloud\SendCloudV2\Api\Data\SelectedFunctionalitiesExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }
}
