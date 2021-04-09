<?php

namespace SendCloud\SendCloudV2\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface SelectedFunctionalitiesInterface extends ExtensibleDataInterface
{
    /**
     * @param \SendCloud\SendCloudV2\Api\Data\SelectedFunctionalitiesExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\SendCloud\SendCloudV2\Api\Data\SelectedFunctionalitiesExtensionInterface $extensionAttributes);

    /**
     * @return \SendCloud\SendCloudV2\Api\Data\SelectedFunctionalitiesExtensionInterface
     */
    public function getExtensionAttributes();
}
