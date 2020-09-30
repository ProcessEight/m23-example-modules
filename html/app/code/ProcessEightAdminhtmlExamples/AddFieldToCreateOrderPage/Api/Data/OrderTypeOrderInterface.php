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

interface OrderTypeOrderInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const KEY_ID = 'order_to_order_type_id';

    const KEY_ORDER_ID = 'order_id';

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
     * @return $this
     */
    public function setId(int $id);

    /**
     * Returns the order ID.
     *
     * @return int Order ID.
     */
    public function getOrderId() : int;

    /**
     * Sets the order ID.
     *
     * @param int $orderId
     *
     * @return $this
     */
    public function setOrderId(int $orderId);

    /**
     * Returns the order type ID.
     *
     * @return int Order type ID
     */
    public function getOrderTypeId() : int;

    /**
     * Sets the order type ID.
     *
     * @param int $orderTypeId
     *
     * @return $this
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
     * @return $this
     */
    public function setReferenceOrderIncrementId(string $referenceOrderIncrementId);

    /**
     * Returns the creation date and time.
     *
     * @return string|null OrderTypeQuote creation date and time. Otherwise, null.
     */
    public function getCreatedAt() : ?string;

    /**
     * Sets the creation date and time.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(string $createdAt);

    /**
     * Returns the update date and time.
     *
     * @return string|null OrderTypeQuote last update date and time. Otherwise, null.
     */
    public function getUpdatedAt() : ?string;

    /**
     * Sets the orderTypeQuote last update date and time.
     *
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt);

}
