<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use SendCloud\SendCloudV2\Api\Data\ShippingProductInterface;

class ShippingProduct extends AbstractExtensibleModel implements ShippingProductInterface
{
    public function getCode()
    {
        return $this->getData('code');
    }

    public function setCode($code)
    {
        $this->setData('code', $code);
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function setName($name)
    {
        $this->setData('name', $name);
    }

    /**
     * @return mixed
     */
    public function getSelectedFunctionalities()
    {
        $data = $this->getData('selected_functionalities');
        $functionalitiesObject = (array)$data->getFunctionalitiesData();
        return json_encode($functionalitiesObject);
    }

    public function setSelectedFunctionalities($selected)
    {
        return $this->setData('selected_functionalities', $selected);
    }

    public function setExtensionAttributes(\SendCloud\SendCloudV2\Api\Data\ShippingProductExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }
}
