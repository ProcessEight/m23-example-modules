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

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data;

/**
 * Interface for order type quote search results.
 *
 */
interface OrderTypeQuoteSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get orderTypeQuotes list.
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface[]
     */
    public function getItems();

    /**
     * Set orderTypeQuotes list.
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface[] $items
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteSearchResultsInterface
     */
    public function setItems(array $items);
}
