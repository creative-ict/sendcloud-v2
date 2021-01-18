<?php

namespace SendCloud\SendCloudV2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;

/**
 * Class SaveServicePointsData
 * @package SendCloud\SendCloudV2\Observer
 */
class SaveServicePointsData implements ObserverInterface
{
    private $quoteRepository;

    /**
     * SaveServicePointsData constructor.
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(QuoteRepository $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * TODO: SC-19: use setSendCloudData
     *
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $quote = $this->quoteRepository->get($order->getQuoteId());
        $order->setSendcloudServicePointId($quote->getSendcloudServicePointId());
        $order->setSendcloudServicePointName($quote->getSendcloudServicePointName());
        $order->setSendcloudServicePointStreet($quote->getSendcloudServicePointStreet());
        $order->setSendcloudServicePointHouseNumber($quote->getSendcloudServicePointHouseNumber());
        $order->setSendcloudServicePointZipCode($quote->getSendcloudServicePointZipCode());
        $order->setSendcloudServicePointCity($quote->getSendcloudServicePointCity());
        $order->setSendcloudServicePointCountry($quote->getSendcloudServicePointCountry());
        $order->setSendcloudServicePointPostnumber($quote->getSendcloudServicePointPostnumber());

        return $this;
    }
}
