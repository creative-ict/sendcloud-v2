<?php

namespace SendCloud\SendCloudV2\Model;

use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Serialize\SerializerInterface;
use SendCloud\SendCloudV2\Api\Data\CheckoutConfigInterface;
use SendCloud\SendCloudV2\Logger\SendCloudLogger;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutConfig;

class CheckoutManagement
{

    /**
     * @var mixed
     */
    protected $checkoutConfig;

    /**
     * @var CheckoutConfigFactory
     */
    private CheckoutConfigFactory $checkoutFactory;

    /**
     * @var CheckoutConfig
     */
    private CheckoutConfig $checkoutResource;

    /**
     * @var SendCloudLogger
     */
    private SendCloudLogger $logger;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param CheckoutConfigInterface $checkoutConfig
     * @param CheckoutConfigFactory $checkoutConfigFactory
     * @param ResourceModel\CheckoutConfig $checkoutConfigResource
     * @param SerializerInterface $serializer
     * @param SendCloudLogger $logger
     */
    public function __construct(
        CheckoutConfigInterface $checkoutConfig,
        CheckoutConfigFactory $checkoutConfigFactory,
        CheckoutConfig $checkoutConfigResource,
        SerializerInterface $serializer,
        SendCloudLogger $logger
    ) {
        $this->checkoutConfig = $checkoutConfig;
        $this->checkoutFactory = $checkoutConfigFactory;
        $this->checkoutResource = $checkoutConfigResource;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param mixed $checkout_configuration
     * @return \SendCloud\SendCloudV2\Model\CheckoutConfig
     * @throws AlreadyExistsException
     */
    public function putCheckout($checkout_configuration)
    {
        $json = $this->serializer->serialize($checkout_configuration);
        $checkoutConfigModel = $this->checkoutFactory->create();
        $configId = $checkout_configuration['id'];

        if ($this->checkoutResource->getIdByConfigId($configId) > 0) {
            $entityId = $this->checkoutResource->getIdByConfigId($configId);
            $this->checkoutResource->load($checkoutConfigModel, $entityId);
        }
        $checkoutConfigModel->setConfigId((string) $checkout_configuration['id']);
        $checkoutConfigModel->setConfigJson($json);

        $this->checkoutResource->save($checkoutConfigModel);

        return $checkoutConfigModel;
    }

    /**
     * @param string $configId
     * @return $this
     * @throws Exception
     */
    public function deleteCheckout($configId)
    {
        $entityId = (int) $this->checkoutResource->getIdByConfigId($configId);
        if (!$entityId) {
            return $this;
        }
        $checkoutConfigModel = $this->checkoutFactory->create();
        $this->checkoutResource->load($checkoutConfigModel, $entityId);
        $this->checkoutResource->delete($checkoutConfigModel);

        return $this;
    }
}
