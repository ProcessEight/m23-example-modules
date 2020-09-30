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

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api;

/**
 * OrderTypeQuote CRUD interface.
 *
 */
interface OrderTypeQuoteRepositoryInterface
{
    /**
     * Save orderTypeQuote.
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface $orderTypeQuote
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\OrderTypeQuoteInterface $orderTypeQuote);

    /**
     * Retrieve orderTypeQuote.
     *
     * @param int $orderTypeQuoteId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $orderTypeQuoteId);

    /**
     * Retrieve orderTypeQuote by quoteId.
     *
     * @param int $quoteId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByQuoteId(int $quoteId);

    /**
     * Retrieve orderTypeQuotes matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete orderTypeQuote.
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface $orderTypeQuote
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\OrderTypeQuoteInterface $orderTypeQuote);

    /**
     * Delete orderTypeQuote by ID.
     *
     * @param int $orderTypeQuoteId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $orderTypeQuoteId);
}
