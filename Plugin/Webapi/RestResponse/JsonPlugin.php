<?php

namespace SendCloud\SendCloudV2\Plugin\Webapi\RestResponse;

use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Webapi\Rest\Response\Renderer\Json;

class JsonPlugin
{
    private $_path = 'extension_attributes/sendcloud_data/checkout_payload/shipping_product/selected_functionalities';

    /** @var Request */
    private $request;

    /**
     * JsonPlugin constructor.
     * @param Request $request
     */
    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    /**
     * @param Json $jsonRenderer
     * @param callable $proceed
     * @param $data
     * @return mixed
     */
    public function aroundRender(Json $jsonRenderer, callable $proceed, $data)
    {
        if (isset($data['extension_attributes']['sendcloud_data']['checkout_payload']['shipping_product']['selected_functionalities'])) {

            $target = $data['extension_attributes']['sendcloud_data']['checkout_payload']['shipping_product']['selected_functionalities'];

            $result = json_decode($target);

            $data['extension_attributes']['sendcloud_data']['checkout_payload']['shipping_product']['selected_functionalities'] = (array)$result;
        }
        return $proceed($data);
    }
}
