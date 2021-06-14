<?php

namespace SendCloud\SendCloudV2\Plugin\Webapi\RestResponse;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Webapi\Rest\Response\Renderer\Json;


class JsonPlugin
{
    private $_path = 'extension_attributes/sendcloud_data/checkout_payload/shipping_product/selected_functionalities';

    /** @var Request */
    private $request;

    /**
     * @var ArrayManager
     */
    private $arrayManager;


    /**
     * JsonPlugin constructor.
     * @param ArrayManager $arrayManager
     * @param Request $request
     */
    public function __construct(
        ArrayManager $arrayManager,
        Request $request
    )
    {
        $this->arrayManager = $arrayManager;
        $this->request = $request;
    }


    /**
     * @param Json $jsonRenderer
     * @param callable $proceed
     * @param $data
     * @return mixed
     */
    public function aroundRender(Json $jsonRenderer, callable $proceed, &$data)
    {
        if(!is_array($data)){
            return $proceed($data);
        }
        if ($this->arrayManager->exists($this->_path, $data)) {
            $data = $this->modifyData($data);
        }
        elseif($this->arrayManager->exists('items', $data) && !$this->arrayManager->exists($this->_path, $data)) {

            foreach($data['items'] as $key => $value)
            {
                $data['items'][$key] = $this->modifyData($value);
            }
        }
        return $proceed($data);
    }


    /**
     * @param $data
     * @return array|mixed
     */
    private function modifyData($data)
    {
        if ($this->arrayManager->exists($this->_path, $data)) {
            $target = $this->arrayManager->get($this->_path, $data) ?? [];
            $result = json_decode($target);
            $data = $this->arrayManager->replace($this->_path, $data, (array)$result);
        }
        return $data;
    }
}
