<?php

namespace SendCloud\SendCloudV2\Plugin\Order;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Model\OrderRepository as MagentoOrderRepository;
use SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface;
use SendCloud\SendCloudV2\Api\Data\NominatedDayDeliveryInterface;
use SendCloud\SendCloudV2\Api\Data\SelectedFunctionalitiesInterface;
use SendCloud\SendCloudV2\Api\Data\SendCloudDataInterface;
use SendCloud\SendCloudV2\Api\Data\ShippingProductInterface;
use SendCloud\SendCloudV2\Logger\SendCloudLogger;
use SendCloud\SendCloudV2\Model\CheckoutPayloadBuilder;
use SendCloud\SendCloudV2\Model\CheckoutPayloadFactory;
use SendCloud\SendCloudV2\Model\NominatedDayDeliveryFactory;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutPayload;
use SendCloud\SendCloudV2\Model\SelectedFunctionalitiesFactory;
use SendCloud\SendCloudV2\Model\SendCloudDataFactory;
use SendCloud\SendCloudV2\Model\ShippingProductFactory;

/**
 * Class OrderRepository
 * @package SendCloud\SendCloudV2\Plugin\Order
 */
class OrderRepository
{
    /** @var OrderExtensionFactory */
    private $orderExtensionFactory;

    /**
     * @var CheckoutPayloadFactory
     */
    private $checkoutPayloadFactory;

    /**
     * @var CheckoutPayload
     */
    private $checkoutPayload;

    /**
     * @var ShippingProductFactory
     */
    private $shippingProductFactory;

    /**
     * @var SendCloudDataFactory
     */
    private $sendCloudDataFactory;

    /**
     * @var SelectedFunctionalitiesFactory
     */
    private $selectedFunctionalitiesFactory;

    /**
     * @var NominatedDayDeliveryFactory
     */
    private $nominatedFactory;

    /**
     * OrderRepository constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param CheckoutPayloadFactory $checkoutPayloadFactory
     * @param CheckoutPayload $checkoutPayload
     * @param ShippingProductFactory $shippingProductFactory
     * @param SendCloudDataFactory $sendCloudDataFactory
     * @param SelectedFunctionalitiesFactory $selectedFunctionalitiesFactory
     * @param NominatedDayDeliveryFactory $nominatedDayDeliveryFactory
     * @param SendCloudLogger $logger
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        CheckoutPayloadFactory $checkoutPayloadFactory,
        CheckoutPayload $checkoutPayload,
        ShippingProductFactory $shippingProductFactory,
        SendCloudDataFactory $sendCloudDataFactory,
        SelectedFunctionalitiesFactory $selectedFunctionalitiesFactory,
        NominatedDayDeliveryFactory $nominatedDayDeliveryFactory,
        SendCloudLogger $logger
    ){
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->checkoutPayloadFactory = $checkoutPayloadFactory;
        $this->checkoutPayload = $checkoutPayload;
        $this->shippingProductFactory = $shippingProductFactory;
        $this->sendCloudDataFactory = $sendCloudDataFactory;
        $this->selectedFunctionalitiesFactory = $selectedFunctionalitiesFactory;
        $this->nominatedFactory = $nominatedDayDeliveryFactory;
        $this->logger = $logger;
    }

    /**
     * @param MagentoOrderRepository $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(MagentoOrderRepository $subject, OrderInterface $order)
    {
        $this->loadSendCloudExtensionAttributes($order);
        $this->getCheckoutPayload($order);
        $this->saveCheckoutPayload($order);

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
            $this->getCheckoutPayload($order);
            $this->saveCheckoutPayload($order);
        }

        return $orderCollection;
    }

    /**
     * @param OrderInterface $order
     * @return OrderInterface
     */
    private function getCheckoutPayload(OrderInterface $order)
    {
        try {
            /** @var CheckoutPayloadInterface $checkoutPayloadModel */
            $checkoutPayloadModel = $this->checkoutPayloadFactory->create();

            /** @var SendCloudDataInterface $sendCloudData */
            $sendCloudData = $this->sendCloudDataFactory->create();
            $checkoutPayloadId = $this->checkoutPayload->getIdByQuoteId($order->getQuoteId());
            $this->checkoutPayload->load($checkoutPayloadModel, $checkoutPayloadId);
            $sendCloudData->setCheckoutPayload($checkoutPayloadModel);
            if (!$checkoutPayloadModel->getEntityId()) {
                throw new NoSuchEntityException();
            }
        } catch (NoSuchEntityException $e) {
            return $order;
        }

        $extensionAttributes = $order->getExtensionAttributes();
        $orderExtension = $extensionAttributes ? $extensionAttributes : $this->orderExtensionFactory->create();

        $orderExtension->setSendcloudData($sendCloudData);
        $order->setExtensionAttributes($orderExtension);

        return $order;
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
        } catch (NoSuchEntityException $e) {
            return $this;
        }
    }

    public function afterSave(MagentoOrderRepository $subject, OrderInterface $order)
    {
        $order = $this->saveCheckoutPayload($order);

        return $order;
    }

    private function saveCheckoutPayload(OrderInterface $order)
    {
        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        } elseif ($extensionAttributes->getSendcloudData() !== null) {
            $checkoutPayloadModel = $this->checkoutPayloadFactory->create();
            $checkoutPayloadId = $this->checkoutPayload->getIdByQuoteId($order->getQuoteId());
            $this->checkoutPayload->load($checkoutPayloadModel, $checkoutPayloadId);

            /** @var SelectedFunctionalitiesInterface $selectedFunctionalities */
            $selectedFunctionalities = $this->selectedFunctionalitiesFactory->create();
            $data = json_decode($checkoutPayloadModel->getSelectedFunctionalities());
            $selectedFunctionalities->setSignature($data->signature);

            /** @var ShippingProductInterface $shippingProduct */
            $shippingProduct = $this->shippingProductFactory->create();
            $shippingProduct->setCode($checkoutPayloadModel->getCode());
            $shippingProduct->setName($checkoutPayloadModel->getName());
            $shippingProduct->setSelectedFunctionalities($selectedFunctionalities);

            /** @var NominatedDayDeliveryInterface $nominatedDay */
            $nominatedDay = $this->nominatedFactory->create();
            $nominatedDay->setDeliveryDate($checkoutPayloadModel->getDeliveryDate());
            $nominatedDay->setFormattedDeliveryDate($checkoutPayloadModel->getFormattedDeliveryDate());
            $nominatedDay->setParcelHandoverDate($checkoutPayloadModel->getParcelHandoverDate());

            /** @var CheckoutPayloadInterface $sendcloudCheckoutPayload */
            $sendcloudCheckoutPayload = $this->checkoutPayloadFactory->create();
            $sendcloudCheckoutPayload->setShippingProduct($shippingProduct);
            $sendcloudCheckoutPayload->setNominatedDayDelivery($nominatedDay);
            $sendcloudCheckoutPayload->setSenderAddressId($checkoutPayloadModel->getSenderAddressId());

            /** @var SendCloudDataInterface $sendCloudData */
            $sendCloudData = $this->sendCloudDataFactory->create();
            $sendCloudData->setCheckoutPayload($sendcloudCheckoutPayload);

            $extensionAttributes->setSendcloudData($sendCloudData);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $order;
    }
}
