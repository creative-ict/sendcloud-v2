<?php

namespace SendCloud\SendCloudV2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use SendCloud\SendCloudV2\Model\CheckoutPayloadFactory;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutPayload;

class AddSendCloudVariable implements ObserverInterface
{
    private $order = null;

    /**
     * @var CheckoutPayload
     */
    private CheckoutPayload $checkoutPayloadResource;

    /**
     * @var CheckoutPayloadFactory
     */
    private CheckoutPayloadFactory $checkoutPayloadFactory;

    public function __construct(
        CheckoutPayload $checkoutPayloadResource,
        CheckoutPayloadFactory $checkoutPayloadFactory
    )
    {
        $this->checkoutPayloadResource = $checkoutPayloadResource;
        $this->checkoutPayloadFactory = $checkoutPayloadFactory;
    }

    public function execute(Observer $observer)
    {
        $transportObject = $observer->getEvent()->getData('transportObject');
        $this->order = $transportObject->getOrder();

        if ($this->order !== null) {
            $quoteId = $this->order->getQuoteId();
            if ($this->order->getSendcloudServicePointId()) {
                $this->getServicePointVariables($transportObject);
            }
            if ($this->getSendCloudData($quoteId)->getEntityId() !== null) {
                $this->getDeliveryOptionsVariables($transportObject, $this->getSendCloudData($quoteId));
            }
        }
    }

    /**
     * @param $transportObject
     */
    private function getServicePointVariables($transportObject)
    {
        /** @var OrderInterface $order */
        $order = $this->order;

        // Set Sendcloud Service Point variables
        $transportObject['sc_servicepoint_id'] = $order->getSendcloudServicePointId();
        $transportObject['sc_servicepoint_name'] = $order->getSendcloudServicePointName();
        $transportObject['sc_servicepoint_street'] = $order->getSendcloudServicePointStreet();
        $transportObject['sc_servicepoint_house_no'] = $order->getSendcloudServicePointHouseNumber();
        $transportObject['sc_servicepoint_zipcode'] = $order->getSendcloudServicePointZipCode();
        $transportObject['sc_servicepoint_city'] = $order->getSendcloudServicePointCity();
        $transportObject['sc_servicepoint_post_no'] = $order->getSendcloudServicePointPostnumber();
    }

    /**
     * @param $quoteId
     * @return \SendCloud\SendCloudV2\Model\CheckoutPayload
     */
    private function getSendCloudData($quoteId)
    {
        $checkoutPayloadId = $this->checkoutPayloadResource->getIdByQuoteId($quoteId);
        $checkoutPayloadModel = $this->checkoutPayloadFactory->create();
        $this->checkoutPayloadResource->load($checkoutPayloadModel, $checkoutPayloadId);

        return $checkoutPayloadModel;
    }

    private function getDeliveryOptionsVariables($transportObject, $sendCloudData)
    {
        $transportObject['sc_delivery_id'] = $sendCloudData->getEntityId();
        $transportObject['sc_carrier_name'] = $sendCloudData->getName();
        $transportObject['sc_delivery_date'] = $sendCloudData->getFormattedDeliveryDate();
    }
}
