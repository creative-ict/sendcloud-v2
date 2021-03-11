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

    public function setParcelHandoverDate($parcelHandoverDate)
    {
        return $this->setData('parcel_handover_date', $parcelHandoverDate);
    }

    public function getParcelHandoverDate()
    {
        return $this->getData('parcel_handover_date');
    }
}
