<?php

namespace SendCloud\SendCloudV2\Block\Order;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Block\Order\Info as Original;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;
use SendCloud\SendCloudV2\ViewModel\SendCloudData;

class Info extends Original
{
    /**
     * @var SendCloudData
     */
    private SendCloudData $sendCloudData;

    /**
     * Info constructor.
     * @param TemplateContext $context
     * @param Registry $registry
     * @param PaymentHelper $paymentHelper
     * @param AddressRenderer $addressRenderer
     * @param SendCloudData $sendCloudData
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        Registry $registry,
        PaymentHelper $paymentHelper,
        AddressRenderer $addressRenderer,
        SendCloudData $sendCloudData,
        array $data = []
    ) {
        parent::__construct($context, $registry, $paymentHelper, $addressRenderer, $data);
        $this->sendCloudData = $sendCloudData;
    }

    /**
     * @var string
     */
    protected $_template = 'SendCloud_SendCloudV2::order/info.phtml';

    public function getSendCloudData($quoteId)
    {
        return $this->sendCloudData->getSendCloudData($quoteId);
    }
}
