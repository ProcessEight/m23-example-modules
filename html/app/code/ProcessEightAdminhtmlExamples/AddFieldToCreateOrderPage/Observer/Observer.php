<?php
/**
 * ProcessEightAdminhtmlExamples
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEightAdminhtmlExamples for more information.
 *
 * @copyright   Copyright (c) 2020 ProcessEightAdminhtmlExamples
 * @author      ProcessEightAdminhtmlExamples
 *
 */

declare(strict_types=1);

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface;

class Observer implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuoteFactory
     */
    private $orderTypeQuoteFactory;

    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterfaceFactory
     */
    private $orderTypeQuoteRepositoryInterfaceFactory;

    /**
     * Observer constructor.
     *
     * @param \Psr\Log\LoggerInterface                                                                              $logger
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuoteFactory                  $orderTypeQuoteFactory
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterfaceFactory $orderTypeQuoteRepositoryInterfaceFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuoteFactory $orderTypeQuoteFactory,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterfaceFactory $orderTypeQuoteRepositoryInterfaceFactory
    ) {
        $this->logger                                   = $logger;
        $this->orderTypeQuoteFactory                    = $orderTypeQuoteFactory;
        $this->orderTypeQuoteRepositoryInterfaceFactory = $orderTypeQuoteRepositoryInterfaceFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $eventData = $observer->getData();

        /** @var \Magento\Sales\Model\AdminOrder\Create $orderCreateModel */
        $orderCreateModel  = $eventData['order_create_model'];
        $orderTypePostData = $orderCreateModel->getData('order-type');

        $quoteId = $orderCreateModel->getQuote()->getId();
        if (!$quoteId || $orderTypePostData === null) {
            $this->logger->debug(__METHOD__, [$orderTypePostData, $quoteId]);

            return $this;
        }

        /** @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterface $orderTypeQuoteRepository */
        $orderTypeQuoteRepository = $this->orderTypeQuoteRepositoryInterfaceFactory->create();

        try {
            $orderTypeQuote = $orderTypeQuoteRepository->getByQuoteId((int)$quoteId);
        } catch (NoSuchEntityException $e) {
            $this->logger->debug(__METHOD__, [(int)$quoteId, $e->getMessage(), $e->getTrace()]);

            return $this;
        }

        $orderTypeQuoteData = [
            OrderTypeQuoteInterface::KEY_ID                           => $orderTypeQuote->getId(),
            OrderTypeQuoteInterface::KEY_QUOTE_ID                     => $quoteId,
            OrderTypeQuoteInterface::KEY_ORDER_TYPE_ID                => $orderTypePostData['order-type-dropdown'],
            OrderTypeQuoteInterface::KEY_REFERENCE_ORDER_INCREMENT_ID => $orderTypePostData['reference-order-input'],
        ];
        $orderTypeQuote->setData($orderTypeQuoteData);
        $orderTypeQuoteRepository->save($orderTypeQuote);

        $this->logger->debug(__METHOD__, $orderTypeQuoteData);

        return $this;
    }
}
