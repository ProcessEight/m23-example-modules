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

use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\OrderTypeQuoteRepositoryInterface;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeQuote as ResourceOrderTypeQuote;
use ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeQuote\CollectionFactory as OrderTypeQuoteCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class OrderTypeQuoteRepository
 *
 */
class OrderTypeQuoteRepository implements OrderTypeQuoteRepositoryInterface
{
    /**
     * @var ResourceOrderTypeQuote
     */
    protected $resource;

    /**
     * @var OrderTypeQuoteFactory
     */
    protected $orderTypeQuoteFactory;

    /**
     * @var OrderTypeQuoteCollectionFactory
     */
    protected $orderTypeQuoteCollectionFactory;

    /**
     * @var Data\OrderTypeQuoteSearchResultsInterfaceFactory
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
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterfaceFactory
     */
    protected $dataOrderTypeQuoteFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param ResourceOrderTypeQuote                           $resource
     * @param OrderTypeQuoteFactory                            $orderTypeQuoteFactory
     * @param Data\OrderTypeQuoteInterfaceFactory              $dataOrderTypeQuoteFactory
     * @param OrderTypeQuoteCollectionFactory                  $orderTypeQuoteCollectionFactory
     * @param Data\OrderTypeQuoteSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper                                 $dataObjectHelper
     * @param DataObjectProcessor                              $dataObjectProcessor
     * @param StoreManagerInterface                            $storeManager
     * @param CollectionProcessorInterface                     $collectionProcessor
     */
    public function __construct(
        ResourceOrderTypeQuote $resource,
        OrderTypeQuoteFactory $orderTypeQuoteFactory,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterfaceFactory $dataOrderTypeQuoteFactory,
        OrderTypeQuoteCollectionFactory $orderTypeQuoteCollectionFactory,
        Data\OrderTypeQuoteSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource                        = $resource;
        $this->orderTypeQuoteFactory           = $orderTypeQuoteFactory;
        $this->orderTypeQuoteCollectionFactory = $orderTypeQuoteCollectionFactory;
        $this->searchResultsFactory            = $searchResultsFactory;
        $this->dataObjectHelper                = $dataObjectHelper;
        $this->dataOrderTypeQuoteFactory       = $dataOrderTypeQuoteFactory;
        $this->dataObjectProcessor             = $dataObjectProcessor;
        $this->storeManager                    = $storeManager;
        $this->collectionProcessor             = $collectionProcessor;
    }

    /**
     * Save OrderTypeQuote data
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface $orderTypeQuote
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\OrderTypeQuoteInterface $orderTypeQuote)
    {
        try {
            $this->resource->save($orderTypeQuote);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $orderTypeQuote;
    }

    /**
     * Load OrderTypeQuote by ID
     *
     * @param int $orderTypeQuoteId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $orderTypeQuoteId)
    {
        $orderTypeQuote = $this->orderTypeQuoteFactory->create();
        $this->resource->load($orderTypeQuote, $orderTypeQuoteId);
        if (!$orderTypeQuote->getId()) {
            throw new NoSuchEntityException(
                __('The OrderTypeQuote record with the ID "%1" doesn\'t exist.', $orderTypeQuoteId)
            );
        }

        return $orderTypeQuote;
    }

    /**
     * Load OrderTypeQuote by Quote ID
     *
     * @param int $quoteId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     * @throws NoSuchEntityException
     */
    public function getByQuoteId(int $quoteId)
    {
        $orderTypeQuote = $this->orderTypeQuoteFactory->create();
        $this->resource->load($orderTypeQuote, $quoteId, OrderTypeQuoteInterface::KEY_QUOTE_ID);

        return $orderTypeQuote;
    }

    /**
     * Load OrderTypeQuote collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeQuote\Collection $collection */
        $collection = $this->orderTypeQuoteCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\OrderTypeQuoteSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete OrderTypeQuote
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface|\ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuote $orderTypeQuote
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\OrderTypeQuoteInterface $orderTypeQuote)
    {
        try {
            $this->resource->delete($orderTypeQuote);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete OrderTypeQuote by given ID
     *
     * @param int $orderTypeQuoteId
     *
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $orderTypeQuoteId)
    {
        return $this->delete($this->getById($orderTypeQuoteId));
    }
}
