<?php

namespace SendCloud\SendCloudV2\Model;

use Exception;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Serialize\SerializerInterface;
use SendCloud\SendCloudV2\Api\Data\CheckoutConfigInterface;
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
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var WriterInterface
     */
    private WriterInterface $writer;

    /**
     * @var TypeListInterface
     */
    private TypeListInterface $cache;

    /**
     * @param CheckoutConfigInterface $checkoutConfig
     * @param CheckoutConfigFactory $checkoutConfigFactory
     * @param ResourceModel\CheckoutConfig $checkoutConfigResource
     * @param SerializerInterface $serializer
     * @param WriterInterface $writer
     * @param TypeListInterface $cache
     */
    public function __construct(
        CheckoutConfigInterface $checkoutConfig,
        CheckoutConfigFactory $checkoutConfigFactory,
        CheckoutConfig $checkoutConfigResource,
        SerializerInterface $serializer,
        WriterInterface $writer,
        TypeListInterface $cache
    ) {
        $this->checkoutConfig = $checkoutConfig;
        $this->checkoutFactory = $checkoutConfigFactory;
        $this->checkoutResource = $checkoutConfigResource;
        $this->serializer = $serializer;
        $this->writer = $writer;
        $this->cache = $cache;
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

        $this->writer->save('carriers/sendcloudv2skeleton/active', 1);
        $this->cache->cleanType('config');

        return $checkoutConfigModel;
    }

    /**
     * @param string $configId
     * @return bool
     * @throws Exception
     */
    public function deleteCheckout(string $configId): bool
    {
        $entityId = (int) $this->checkoutResource->getIdByConfigId($configId);
        if (!$entityId) {
            return false;
        }
        $checkoutConfigModel = $this->checkoutFactory->create();
        $this->checkoutResource->load($checkoutConfigModel, $entityId);
        $this->checkoutResource->delete($checkoutConfigModel);

        $this->writer->save('carriers/sendcloudv2skeleton/active', 0);
        $this->cache->cleanType('config');

        return true;
    }
}
