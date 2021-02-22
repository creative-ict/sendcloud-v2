<?php

namespace SendCloud\SendCloudV2\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use SendCloud\SendCloudV2\Model\CheckoutPayloadFactory;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutPayload;

class SendCloudData implements ArgumentInterface
{
    /**
     * @var CheckoutPayload
     */
    private CheckoutPayload $checkoutPayloadResource;

    /**
     * @var CheckoutPayloadFactory
     */
    private CheckoutPayloadFactory $checkoutPayloadFactory;

    /**
     * SendCloudData constructor.
     * @param CheckoutPayload $checkoutPayloadResource
     * @param CheckoutPayloadFactory $checkoutPayloadFactory
     */
    public function __construct(
        CheckoutPayload $checkoutPayloadResource,
        CheckoutPayloadFactory $checkoutPayloadFactory
    ) {
        $this->checkoutPayloadResource = $checkoutPayloadResource;
        $this->checkoutPayloadFactory = $checkoutPayloadFactory;
    }

    /**
     * @param $quoteID
     * @return \SendCloud\SendCloudV2\Model\CheckoutPayload
     */
    public function getSendCloudData($quoteID)
    {
        $checkoutPayloadId = $this->checkoutPayloadResource->getIdByQuoteId($quoteID);
        $checkoutPayloadModel = $this->checkoutPayloadFactory->create();
        $this->checkoutPayloadResource->load($checkoutPayloadModel, $checkoutPayloadId);

        return $checkoutPayloadModel;
    }
}
