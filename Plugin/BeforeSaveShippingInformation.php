<?php

namespace SendCloud\SendCloudV2\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Quote\Model\QuoteRepository;
use SendCloud\SendCloudV2\Helper\Checkout;
use SendCloud\SendCloudV2\Model\CheckoutPayloadBuilder;
use SendCloud\SendCloudV2\Model\CheckoutPayloadFactory;
use SendCloud\SendCloudV2\Model\ResourceModel\CheckoutPayload;

/**
 * Class BeforeSaveShippingInformation
 * @package SendCloud\SendCloudV2\Plugin
 */
class BeforeSaveShippingInformation
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var Checkout
     */
    private $helper;

    /**
     * @var CheckoutPayloadFactory
     */
    private $checkoutPayloadFactory;

    /**
     * @var CheckoutPayload
     */
    private $checkoutPayload;

    /**
     * @var CheckoutPayloadBuilder
     */
    private $checkoutPayloadBuilder;

    /**
     * BeforeSaveShippingInformation constructor.
     * @param RequestInterface $request
     * @param QuoteRepository $quoteRepository
     * @param Checkout $helper
     * @param CheckoutPayloadFactory $checkoutPayloadFactory
     * @param CheckoutPayload $checkoutPayload
     */
    public function __construct(
        RequestInterface $request,
        QuoteRepository $quoteRepository,
        Checkout $helper,
        CheckoutPayloadFactory $checkoutPayloadFactory,
        CheckoutPayload $checkoutPayload
    ) {
        $this->request = $request;
        $this->quoteRepository = $quoteRepository;
        $this->helper = $helper;
        $this->checkoutPayloadFactory = $checkoutPayloadFactory;
        $this->checkoutPayload = $checkoutPayload;
    }

    /**
     * TODO: SC-19: use setSendCloudData
     *
     * @param ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(ShippingInformationManagement $subject, $cartId, ShippingInformationInterface $addressInformation)
    {
        $extensionAttributes = $addressInformation->getExtensionAttributes();
        if ($extensionAttributes != null) {
            $quote = $this->quoteRepository->getActive($cartId);

            if ($extensionAttributes->getSendcloudData()) {
                $checkoutPayload = $extensionAttributes->getSendcloudData()->getCheckoutPayload();
                $sendcloudCheckoutPayload = [$checkoutPayload->getCheckoutPayload()];

                $shippingProduct = $checkoutPayload->getShippingProduct();
                $nominatedDayDelivery = $checkoutPayload->getNominatedDayDelivery();
                $senderAddressId = $checkoutPayload->getSenderAddressId();

                $checkoutPayloadModel = $this->checkoutPayloadFactory->create();
                if ($this->checkoutPayload->getIdByQuoteId($quote->getEntityId())) {
                    $checkoutPayloadId = $this->checkoutPayload->getIdByQuoteId($quote->getEntityId());
                    $this->checkoutPayload->load($checkoutPayloadModel, $checkoutPayloadId);
                }
                $checkoutPayload = $this->getCheckoutPayloadBuilderDependency();

                $checkoutPayload->setOrderId($quote->getEntityId());

                $checkoutPayloadModel->setQuoteId($quote->getEntityId());
                $checkoutPayloadModel->setCode($shippingProduct->getCode());
                $checkoutPayloadModel->setName($shippingProduct->getName());
                $checkoutPayloadModel->setSelectedFunctionalities($shippingProduct->getSelectedFunctionalities());
                $checkoutPayloadModel->setDeliveryDate($nominatedDayDelivery->getDeliveryDate());
                $checkoutPayloadModel->setFormattedDeliveryDate($nominatedDayDelivery->getFormattedDeliveryDate());
                $checkoutPayloadModel->setParcelHandoverDate($nominatedDayDelivery->getParcelHandoverDate());
                $checkoutPayloadModel->setSenderAddressId($senderAddressId);

                $this->checkoutPayload->save($checkoutPayloadModel);
                $quote->setSendcloudCheckoutPayload(json_encode($sendcloudCheckoutPayload));
            }
        }

        if ($this->helper->checkForScriptUrl() && $extensionAttributes != null && $this->helper->checkIfModuleIsActive()) {
            $spId = $extensionAttributes->getSendcloudServicePointId();
            $spName = $extensionAttributes->getSendcloudServicePointName();
            $spStreet = $extensionAttributes->getSendcloudServicePointStreet();
            $spHouseNumber = $extensionAttributes->getSendcloudServicePointHouseNumber();
            $spZipCode = $extensionAttributes->getSendcloudServicePointZipCode();
            $spCity = $extensionAttributes->getSendcloudServicePointCity();
            $spCountry = $extensionAttributes->getSendcloudServicePointCountry();
            $spPostnumber = $extensionAttributes->getSendcloudServicePointPostnumber();

            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setSendcloudServicePointId($spId);
            $quote->setSendcloudServicePointName($spName);
            $quote->setSendcloudServicePointStreet($spStreet);
            $quote->setSendcloudServicePointHouseNumber($spHouseNumber);
            $quote->setSendcloudServicePointZipCode($spZipCode);
            $quote->setSendcloudServicePointCity($spCity);
            $quote->setSendcloudServicePointCountry($spCountry);
            $quote->setSendcloudServicePointPostnumber($spPostnumber);
        }
    }

    /**
     * @return CheckoutPayloadBuilder
     */
    private function getCheckoutPayloadBuilderDependency()
    {
        if (!$this->checkoutPayloadBuilder instanceof CheckoutPayloadBuilder) {
            $this->checkoutPayloadBuilder = \Magento\Framework\App\ObjectManager::getInstance()->get(
                CheckoutPayloadBuilder::class
            );
        }
        return $this->checkoutPayloadBuilder;
    }
}
