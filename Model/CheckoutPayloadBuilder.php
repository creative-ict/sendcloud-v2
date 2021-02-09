<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Sales\Model\OrderFactory;
use SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface;
use SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterfaceFactory;
use SendCloud\SendCloudV2\Api\Data\ShippingProductInterface;
use SendCloud\SendCloudV2\Model\ShippingProductFactory;

class CheckoutPayloadBuilder
{
    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var CheckoutPayloadInterfaceFactory
     */
    private $checkoutPayloadFactory;

    /**
     * @var int|null
     */
    private $orderId = null;

    /**
     * @var ShippingProductFactory
     */
    private $shippingProductFactory;

    public function __construct(
        OrderFactory $orderFactory,
        CheckoutPayloadInterfaceFactory $checkoutPayloadInterfaceFactory,
        ShippingProductFactory $shippingProductFactory
    )
    {
        $this->orderFactory = $orderFactory;
        $this->checkoutPayloadFactory = $checkoutPayloadInterfaceFactory;
        $this->shippingProductFactory = $shippingProductFactory;
    }

    /**
     * @param int $orderId
     * @return void
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    private function getOrderId()
    {
        return $this->orderId;
    }

    public function create()
    {
        $checkoutPayloads = null;
        if ($this->getOrderId()) {
            /** @var CheckoutPayloadInterfaceFactory $checkoutPayload */
            $checkoutPayload = $this->checkoutPayloadFactory->create();

            $shippingProduct = $this->shippingProductFactory->create();
            $shippingProduct->setOrderId($this->getOrderId());
            $checkoutPayload->setShippingProduct($shippingProduct);

            $checkoutPayloads = [$checkoutPayload];
        }

        return $checkoutPayloads;
    }
}
