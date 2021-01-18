<?php

namespace SendCloud\SendCloudV2\Model\Config\Source;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Option\ArrayInterface;
use SendCloud\SendCloudV2\Model\Carrier\SendcloudServicepoint;

/**
 * Class Servicepointrate
 * @package SendCloud\SendCloudV2\Model\Config\Source
 *
 * TODO: class Servicepointrate implements \Magento\Framework\Option\OptionSourceInterface
 * use OptionSourceInterface as ArrayInterface is deprecated, but OptionSourceInterface gives compile error when used.
 */
class Servicepointrate implements ArrayInterface
{
    /**
     * @var SendcloudServicepoint
     */
    protected $_carrierServicepointrate;


    /**
     * Servicepointrate constructor.
     * @param SendcloudServicepoint $carrierServicepointrate
     */
    public function __construct(SendcloudServicepoint $carrierServicepointrate)
    {
        $this->_carrierServicepointrate = $carrierServicepointrate;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function toOptionArray()
    {
        $arr = [];
        foreach ($this->_carrierServicepointrate->getCode('sen_condition_name') as $k => $v) {
            $arr[] = ['value' => $k, 'label' => $v];
        }
        return $arr;
    }
}
