<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use SendCloud\SendCloudV2\Api\CheckoutPayloadRepositoryInterface;
use SendCloud\SendCloudV2\Api\Data\CheckoutPayloadInterface;

class CheckoutPayloadRepository implements CheckoutPayloadRepositoryInterface
{
    /**
     * @var CheckoutPayloadFactory
     */
    private $checkoutPayloadFactory;

    /**
     * @var ResourceModel\CheckoutPayload
     */
    private $checkoutPayload;

    public function __construct(CheckoutPayloadFactory $checkoutPayloadFactory, \SendCloud\SendCloudV2\Model\ResourceModel\CheckoutPayload $checkoutPayload)
    {
        $this->checkoutPayloadFactory = $checkoutPayloadFactory;
        $this->checkoutPayload = $checkoutPayload;
    }

    public function getById($id)
    {
        $checkoutPayloadModel = $this->checkoutPayloadFactory->create();
        return $this->checkoutPayload->load($checkoutPayloadModel, $id);
    }

    public function save(CheckoutPayloadInterface $checkoutPayload)
    {
        $checkoutPayloadModel = $this->checkoutPayloadFactory->create()->setData($checkoutPayload);
        $this->checkoutPayload->save($checkoutPayloadModel);
    }
}
