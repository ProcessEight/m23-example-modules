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
 * Interface for order type order search results.
 *
 */
interface OrderTypeOrderSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get orderTypeOrders list.
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface[]
     */
    public function getItems();

    /**
     * Set orderTypeOrders list.
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface[] $items
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderSearchResultsInterface
     */
    public function setItems(array $items);
}
