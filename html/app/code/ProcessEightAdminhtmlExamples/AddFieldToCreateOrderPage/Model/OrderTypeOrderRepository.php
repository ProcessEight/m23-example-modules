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

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model;

use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeOrderRepositoryInterface;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeOrder as ResourceOrderTypeOrder;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeOrder\CollectionFactory as OrderTypeOrderCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class OrderTypeOrderRepository
 *
 */
class OrderTypeOrderRepository implements OrderTypeOrderRepositoryInterface
{
    /**
     * @var ResourceOrderTypeOrder
     */
    protected $resource;

    /**
     * @var OrderTypeOrderFactory
     */
    protected $orderTypeOrderFactory;

    /**
     * @var OrderTypeOrderCollectionFactory
     */
    protected $orderTypeOrderCollectionFactory;

    /**
     * @var Data\OrderTypeOrderSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterfaceFactory
     */
    protected $dataOrderTypeOrderFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param ResourceOrderTypeOrder                           $resource
     * @param OrderTypeOrderFactory                            $orderTypeOrderFactory
     * @param Data\OrderTypeOrderInterfaceFactory              $dataOrderTypeOrderFactory
     * @param OrderTypeOrderCollectionFactory                  $orderTypeOrderCollectionFactory
     * @param Data\OrderTypeOrderSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper                                 $dataObjectHelper
     * @param DataObjectProcessor                              $dataObjectProcessor
     * @param StoreManagerInterface                            $storeManager
     * @param CollectionProcessorInterface                     $collectionProcessor
     */
    public function __construct(
        ResourceOrderTypeOrder $resource,
        OrderTypeOrderFactory $orderTypeOrderFactory,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterfaceFactory $dataOrderTypeOrderFactory,
        OrderTypeOrderCollectionFactory $orderTypeOrderCollectionFactory,
        Data\OrderTypeOrderSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource                        = $resource;
        $this->orderTypeOrderFactory           = $orderTypeOrderFactory;
        $this->orderTypeOrderCollectionFactory = $orderTypeOrderCollectionFactory;
        $this->searchResultsFactory            = $searchResultsFactory;
        $this->dataObjectHelper                = $dataObjectHelper;
        $this->dataOrderTypeOrderFactory       = $dataOrderTypeOrderFactory;
        $this->dataObjectProcessor             = $dataObjectProcessor;
        $this->storeManager                    = $storeManager;
        $this->collectionProcessor             = $collectionProcessor;
    }

    /**
     * Save OrderTypeOrder data
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface $orderTypeOrder
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\OrderTypeOrderInterface $orderTypeOrder)
    {
        try {
            $this->resource->save($orderTypeOrder);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $orderTypeOrder;
    }

    /**
     * Load OrderTypeOrder by ID
     *
     * @param int $orderTypeOrderId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $orderTypeOrderId)
    {
        $orderTypeOrder = $this->orderTypeOrderFactory->create();
        $this->resource->load($orderTypeOrder, $orderTypeOrderId);
        if (!$orderTypeOrder->getId()) {
            throw new NoSuchEntityException(
                __('The OrderTypeOrder record with the ID "%1" doesn\'t exist.', $orderTypeOrderId)
            );
        }

        return $orderTypeOrder;
    }

    /**
     * Load OrderTypeOrder by Order ID
     *
     * @param int $orderId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface
     * @throws NoSuchEntityException
     */
    public function getByOrderId(int $orderId)
    {
        $orderTypeOrder = $this->orderTypeOrderFactory->create();
        $this->resource->load($orderTypeOrder, $orderId, OrderTypeOrderInterface::KEY_ORDER_ID);

        return $orderTypeOrder;
    }

    /**
     * Load OrderTypeOrder collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeOrder\Collection $collection */
        $collection = $this->orderTypeOrderCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\OrderTypeOrderSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete OrderTypeOrder
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface|\ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeOrder $orderTypeOrder
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\OrderTypeOrderInterface $orderTypeOrder)
    {
        try {
            $this->resource->delete($orderTypeOrder);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete OrderTypeOrder by given ID
     *
     * @param int $orderTypeOrderId
     *
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $orderTypeOrderId)
    {
        return $this->delete($this->getById($orderTypeOrderId));
    }
}
