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
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface;

class SubmitOrder implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeOrderFactory
     */
    private $orderTypeOrderFactory;

    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeOrderRepositoryInterfaceFactory
     */
    private $orderTypeOrderRepositoryInterfaceFactory;
    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuoteFactory
     */
    private $orderTypeQuoteFactory;
    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterfaceFactory
     */
    private $orderTypeQuoteRepositoryInterfaceFactory;

    /**
     * SubmitOrder constructor.
     *
     * @param \Psr\Log\LoggerInterface                                                                              $logger
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuoteFactory                  $orderTypeQuoteFactory
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterfaceFactory $orderTypeQuoteRepositoryInterfaceFactory
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeOrderFactory                  $orderTypeOrderFactory
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeOrderRepositoryInterfaceFactory $orderTypeOrderRepositoryInterfaceFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuoteFactory $orderTypeQuoteFactory,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterfaceFactory $orderTypeQuoteRepositoryInterfaceFactory,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeOrderFactory $orderTypeOrderFactory,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeOrderRepositoryInterfaceFactory $orderTypeOrderRepositoryInterfaceFactory
    ) {
        $this->logger                                   = $logger;
        $this->orderTypeQuoteFactory                    = $orderTypeQuoteFactory;
        $this->orderTypeQuoteRepositoryInterfaceFactory = $orderTypeQuoteRepositoryInterfaceFactory;
        $this->orderTypeOrderFactory                    = $orderTypeOrderFactory;
        $this->orderTypeOrderRepositoryInterfaceFactory = $orderTypeOrderRepositoryInterfaceFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        /** @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterface $orderTypeQuoteRepository */
        $orderTypeQuoteRepository = $this->orderTypeQuoteRepositoryInterfaceFactory->create();

        try {
            $orderTypeQuote = $orderTypeQuoteRepository->getByQuoteId((int)$quote->getId());
        } catch (NoSuchEntityException $e) {
            $this->logger->debug(__METHOD__, [(int)$quote->getId(), $e->getMessage(), $e->getTrace()]);

            return $this;
        }

        $orderIncrementId = $order->getIncrementId();
        if (!$orderIncrementId) {
            $this->logger->debug(__METHOD__, [$orderIncrementId]);

            return $this;
        }

        /** @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeOrderRepositoryInterface $orderTypeOrderRepository */
        $orderTypeOrderRepository = $this->orderTypeOrderRepositoryInterfaceFactory->create();

        try {
            $orderTypeOrder = $orderTypeOrderRepository->getByOrderId((int)$order->getId());
        } catch (NoSuchEntityException $e) {
            $this->logger->debug(__METHOD__, [(int)$order->getId(), $e->getMessage(), $e->getTrace()]);

            return $this;
        }

        $orderTypeOrderData = [
            OrderTypeOrderInterface::KEY_ID                           => $orderTypeOrder->getId(),
            OrderTypeOrderInterface::KEY_ORDER_ID                     => $order->getId(),
            OrderTypeOrderInterface::KEY_ORDER_TYPE_ID                => (int)$orderTypeQuote->getOrderTypeId(),
            OrderTypeOrderInterface::KEY_REFERENCE_ORDER_INCREMENT_ID => $orderTypeQuote->getReferenceOrderIncrementId(),
        ];

        $orderTypeOrder->setData($orderTypeOrderData);
        $orderTypeOrderRepository->save($orderTypeOrder);

        return $this;
    }
}
