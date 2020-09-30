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

interface OrderTypeQuoteInterface
{
    /**#@+
     * Constants for keys of data array
     */
    const KEY_ID = 'quote_to_order_type_id';

    const KEY_QUOTE_ID = 'quote_id';

    const KEY_ORDER_TYPE_ID = 'order_type_id';

    const KEY_REFERENCE_ORDER_INCREMENT_ID = 'reference_order_increment_id';

    const KEY_CREATED_AT = 'created_at';

    const KEY_UPDATED_AT = 'updated_at';


    /**
     * Returns the row ID.
     *
     * @return int Auto-increment ID.
     */
    public function getId();

    /**
     * Sets the row ID.
     *
     * @param int $id
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     */
    public function setId(int $id);

    /**
     * Returns the quote ID.
     *
     * @return int Quote ID.
     */
    public function getQuoteId() : int;

    /**
     * Sets the quote ID.
     *
     * @param int $quoteId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     */
    public function setQuoteId(int $quoteId);

    /**
     * Returns the order type ID.
     *
     * @return int Order type ID
     */
    public function getOrderTypeId();

    /**
     * Sets the order type ID.
     *
     * @param int $orderTypeId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     */
    public function setOrderTypeId(int $orderTypeId);

    /**
     * Returns the reference order increment ID.
     *
     * @return string Reference order increment ID.
     */
    public function getReferenceOrderIncrementId() : string;

    /**
     * Sets the reference order increment ID.
     *
     * @param string $referenceOrderIncrementId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     */
    public function setReferenceOrderIncrementId(string $referenceOrderIncrementId);

    /**
     * Returns the orderTypeQuote creation date and time.
     *
     * @return string|null OrderTypeQuote creation date and time. Otherwise, null.
     */
    public function getCreatedAt() : ?string;

    /**
     * Sets the orderTypeQuote creation date and time.
     *
     * @param string $createdAt
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     */
    public function setCreatedAt(string $createdAt);

    /**
     * Returns the orderTypeQuote last update date and time.
     *
     * @return string|null OrderTypeQuote last update date and time. Otherwise, null.
     */
    public function getUpdatedAt() : ?string;

    /**
     * Sets the orderTypeQuote last update date and time.
     *
     * @param string $updatedAt
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface
     */
    public function setUpdatedAt(string $updatedAt);

}
