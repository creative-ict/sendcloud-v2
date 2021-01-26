<?php

namespace SendCloud\SendCloudV2\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Quote\Model\QuoteRepository;
use SendCloud\SendCloudV2\Helper\Checkout;

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
     * BeforeSaveShippingInformation constructor.
     * @param RequestInterface $request
     * @param QuoteRepository $quoteRepository
     * @param Checkout $helper
     */
    public function __construct(
        RequestInterface $request,
        QuoteRepository $quoteRepository,
        Checkout $helper
    )
    {
        $this->request = $request;
        $this->quoteRepository = $quoteRepository;
        $this->helper = $helper;
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

        if ($extensionAttributes != null ) {
            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setSendcloudCheckoutPayload($extensionAttributes->getSendcloudCheckoutPayload());
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
}
