<?php

namespace SendCloud\SendCloudV2\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ShippingProductInterface extends ExtensibleDataInterface
{
    /**
     * @return string
     */
    public function getCode();

    /**
     * @param $code
     * @return mixed
     */
    public function setCode($code);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);

    
    /**
     * @return mixed
     */
    public function getSelectedFunctionalities();

    /**
     * @param string $selected
     * @return $this
     */
    public function setSelectedFunctionalities($selected);

    /**
     * @param \SendCloud\SendCloudV2\Api\Data\ShippingProductExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\SendCloud\SendCloudV2\Api\Data\ShippingProductExtensionInterface $extensionAttributes);

    /**
     * @return \SendCloud\SendCloudV2\Api\Data\ShippingProductExtensionInterface
     */
    public function getExtensionAttributes();
}
