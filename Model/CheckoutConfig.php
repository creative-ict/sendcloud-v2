<?php

namespace SendCloud\SendCloudV2\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\SerializerInterface;
use SendCloud\SendCloudV2\Api\Data\CheckoutConfigInterface;

class CheckoutConfig extends AbstractModel implements
    CheckoutConfigInterface,
    IdentityInterface
{
    const CACHE_TAG = 'sendcloud_sendcloudv2_checkoutconfig';
    protected $_cacheTag = 'sendcloud_sendcloudv2_checkoutconfig';
    protected $_eventPrefix = 'sendcloud_sendcloudv2_checkoutconfig';

    private $checkoutConfig;
    private $zones;
    private $methods;
    private $zoneMethods;

    private $serializer;

    protected function _construct()
    {
        $this->_init('SendCloud\SendCloudV2\Model\ResourceModel\CheckoutConfig');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getConfigJson()
    {
        return $this->getData('config_json');
    }

    public function setConfigJson($json)
    {
        return $this->setData('config_json', $json);
    }

    /**
     * @param string $id
     * @return mixed|CheckoutConfig
     */
    public function setConfigId($id)
    {
        return $this->setData('config_id', $id);
    }

    /**
     * @return string
     */
    public function getConfigId()
    {
        return $this->getData('config_id');
    }

}
