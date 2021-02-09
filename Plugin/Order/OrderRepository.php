<?php

namespace SendCloud\SendCloudV2\Plugin\Order;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Model\OrderRepository as MagentoOrderRepository;
use SendCloud\SendCloudV2\Model\SendcloudCheckoutPayload;

/**
 * Class OrderRepository
 * @package SendCloud\SendCloudV2\Plugin\Order
 */
class OrderRepository
{
    /** @var OrderExtensionFactory */
    private $orderExtensionFactory;

    /** @var SendcloudCheckoutPayload */
    private $sendcloudCheckoutPayload;

    /**
     * OrderRepository constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param SendcloudCheckoutPayload $sendcloudCheckoutPayload
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        SendcloudCheckoutPayload $sendcloudCheckoutPayload
    ){
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->sendcloudCheckoutPayload = $sendcloudCheckoutPayload;
    }

    /**
     * @param MagentoOrderRepository $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(MagentoOrderRepository $subject, OrderInterface $order)
    {
        $this->loadSendCloudExtensionAttributes($order);
        return $order;
    }

    /**
     * @param MagentoOrderRepository $subject
     * @param OrderSearchResultInterface $orderCollection
     * @return OrderSearchResultInterface
     */
    public function afterGetList(MagentoOrderRepository $subject, OrderSearchResultInterface $orderCollection)
    {
        foreach ($orderCollection->getItems() as $order) {
            $this->loadSendCloudExtensionAttributes($order);
        }

        return $orderCollection;
    }

    /**
     * TODO: SC-19: use setSendCloudData
     *
     * @param OrderInterface $order
     * @return $this
     */
    private function loadSendCloudExtensionAttributes(OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        if ($extensionAttributes->getSendcloudServicePointId() !== null) {
            return $this;
        }

        try {
            $extensionAttributes->setSendcloudServicePointId($order->getSendcloudServicePointId());
            $extensionAttributes->setSendcloudServicePointName($order->getSendcloudServicePointName());
            $extensionAttributes->setSendcloudServicePointStreet($order->getSendcloudServicePointStreet());
            $extensionAttributes->setSendcloudServicePointHouseNumber($order->getSendcloudServicePointHouseNumber());
            $extensionAttributes->setSendcloudServicePointZipCode($order->getSendcloudServicePointZipCode());
            $extensionAttributes->setSendcloudServicePointCity($order->getSendcloudServicePointCity());
            $extensionAttributes->setSendcloudServicePointCountry($order->getSendcloudServicePointCountry());
            $extensionAttributes->setSendcloudServicePointPostnumber($order->getSendcloudServicePointPostnumber());



            // JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
//            $data = json_decode($order->getSendcloudCheckoutPayload(), true);
            //$data = json_decode($order->getSendcloudCheckoutPayload(), false,4, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            //$data = json_decode($order->getSendcloudCheckoutPayload(), true,10, JSON_UNESCAPED_SLASHES);


            $this->sendcloudCheckoutPayload->setSendcloudCheckoutPayload($order->getSendcloudCheckoutPayload());

//            print_r($data);exit;
            $extensionAttributes->setSendcloudCheckoutPayload($this->sendcloudCheckoutPayload);
//
//            $extensionAttributes->setSendcloudCheckoutPayload($order->getSendcloudCheckoutPayload());
        } catch (NoSuchEntityException $e) {
            return $this;
        }
    }
}
