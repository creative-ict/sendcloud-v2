<?php

namespace SendCloud\SendCloudV2\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface NominatedDayDeliveryInterface extends ExtensibleDataInterface
{
    /**
     * @param $deliveryDate
     * @return mixed
     */
    public function setDeliveryDate($deliveryDate);

    /**
     * @return mixed
     */
    public function getDeliveryDate();

    /**
     * @param $formattedDeliveryDate
     * @return mixed
     */
    public function setFormattedDeliveryDate($formattedDeliveryDate);

    /**
     * @return mixed
     */
    public function getFormattedDeliveryDate();

    /**
     * @param $parcelHandoverDate
     * @return mixed
     */
    public function setParcelHandoverDate($parcelHandoverDate);

    /**
     * @return mixed
     */
    public function getParcelHandoverDate();
}
