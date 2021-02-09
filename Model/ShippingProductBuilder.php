<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Sales\Model\OrderFactory;
use SendCloud\SendCloudV2\Api\Data\ShippingProductInterface;
use SendCloud\SendCloudV2\Api\Data\ShippingProductInterfaceFactory;

class ShippingProductBuilder
{
    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var ShippingProductInterfaceFactory
     */
    private $shippingProductFactory;

    /**
     * @var int|null
     */
    private $orderId = null;

    /**
     * @var CheckoutPayload
     */
    private $checkoutPayload;

    public function __construct(
        OrderFactory $orderFactory,
        CheckoutPayloadFactory $checkoutPayload,
        ShippingProductInterfaceFactory $shippingProductInterfaceFactory
    )
    {
        $this->orderFactory = $orderFactory;
        $this->checkoutPayload = $checkoutPayload;
        $this->shippingProductFactory = $shippingProductInterfaceFactory;
    }

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
        $shippingProduct = null;
        if ($this->getOrderId()) {
            $order = $this->orderFactory->create()->load($this->getOrderId());
            $this->checkoutPayload = $this->checkoutPayload->create()->load($this->getOrderId());
            if ($order->getEntityId()) {
                /** @var ShippingProductInterface $shippingProduct */
                $shippingProduct = $this->shippingProductFactory->create();

            }
        }
    }
}
