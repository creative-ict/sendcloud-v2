<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use SendCloud\SendCloudV2\Api\Data\NominatedDayDeliveryInterface;

class NominatedDayDelivery extends AbstractExtensibleModel implements NominatedDayDeliveryInterface
{

    public function setDeliveryDate($deliveryDate)
    {
        return $this->setData('delivery_date', $deliveryDate);
    }

    public function getDeliveryDate()
    {
        return $this->getData('delivery_date');
    }

    public function setFormattedDeliveryDate($formattedDeliveryDate)
    {
        return $this->setData('formatted_delivery_date', $formattedDeliveryDate);
    }

    public function getFormattedDeliveryDate()
    {
        return $this->getData('formatted_delivery_date');
    }

    public function setProcessingDate($processingDate)
    {
        return $this->setData('processing_date', $processingDate);
    }

    public function getProcessingDate()
    {
        return $this->getData('processing_date');
    }
}
