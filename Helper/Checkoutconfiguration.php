<?php

namespace SendCloud\SendCloudV2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject as Varien;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Serialize\SerializerInterface;
use SendCloud\SendCloudV2\Logger\SendCloudLogger;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutConfig\CollectionFactory;

/**
 * Class Checkout
 * @package SendCloud\SendCloudV2\Helper
 */
class Checkoutconfiguration extends AbstractHelper
{

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $checkoutConfigCollection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    private $checkoutConfig;
    private $methods = [];
    private $allMethods = [];
    private $blockMethods = null;
    private $zones = [];

    /**
     * @var ResolverInterface
     */
    private ResolverInterface $localeResolver;


    /**
     * Checkoutconfiguration constructor.
     * @param Context $context
     * @param SendCloudLogger $sendCloudLogger
     * @param CollectionFactory $checkoutConfigCollection
     * @param SerializerInterface $serializer
     * @param ResolverInterface $localeResolver
     */
    public function __construct(
        Context $context,
        SendCloudLogger $sendCloudLogger,
        CollectionFactory $checkoutConfigCollection,
        SerializerInterface $serializer,
        ResolverInterface $localeResolver
    )
    {
        parent::__construct($context);
        $this->sendCloudLogger = $sendCloudLogger;
        $this->checkoutConfigCollection = $checkoutConfigCollection;
        $this->serializer = $serializer;
        $this->localeResolver = $localeResolver;
        $this->checkoutConfig = $this->getCheckoutConfig();
        $this->getDeliveryZoneMethods();
    }


    /**
     * get zones, get methods. Make arrays+ object for methods
     */
    private function getDeliveryZoneMethods()
    {
        $configJson = $this->getCheckoutConfig();
        if (!$configJson) {
            return false;
        }
        foreach ($configJson['delivery_zones'] as $item)
        {
            $countryIso2 = $item['location']['country']['iso_2'];
            $this->zones[] = $countryIso2;

            foreach ($item['delivery_methods'] as $method)
            {
                $varien = [
                    'delivery_method'  => $method,
                    'carrier_name' => $method['carrier']['name'],
                    'carrier_logo' => ($method['carrier']['logo_url'] ?? false),
                    'code' => $method['internal_title'],
                    'title' => $method['external_title'],
                    'name' => $method['internal_title'],
                    'type' => $method['delivery_method_type'],
                    'id' => $method['id'],
                    'carrier' => $method['carrier'],
                    'shipping_product' => $method['shipping_product'],
                ];

                $this->methods[$countryIso2][] = new Varien($varien);
                $this->blockMethods[$method['id']] = $method;
                $this->allMethods[] = new Varien($varien);
            }
        }
    }


    /**
     * @return array
     */
    public function getMethods()
    {
        if(!isset($this->methods)) {
            $this->getDeliveryZoneMethods();
        }
        return $this->methods;
    }

    /**
     * @return array
     */
    public function getZones()
    {
        if(!isset($this->zones)) {
            $this->getDeliveryZoneMethods();
        }
        return $this->zones;
    }

    /**
     * @return array
     */
    public function getAllMethods()
    {
        if(!isset($this->allMethods)) {
            $this->getDeliveryZoneMethods();
        }
        return $this->allMethods;
    }

    /**
     * @return array
     */
    public function getBlockMethods()
    {
        if(!isset($this->blockMethods)) {
            $this->getDeliveryZoneMethods();
        }
        return $this->blockMethods;
    }

    /**
     * @return array|bool|float|int|string|null
     */
    public function getCheckoutConfig()
    {
        if(!isset($this->checkoutConfig)) {
            if ($this->checkoutConfigCollection->create()->getLastItem()->getConfigJson()) {
                $this->checkoutConfig = $this->serializer->unserialize($this->checkoutConfigCollection->create()->getLastItem()->getConfigJson());
            }
        }
        return $this->checkoutConfig;
    }

    /**
     * @return array|string|string[]
     */
    public function getCurrentLocale()
    {
        $currentLocale = $this->localeResolver->getLocale();
        return str_replace('_', '-', $currentLocale);
    }
}
