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

use \Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\Model\AbstractModel;
use \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeQuoteInterface;

/**
 * OrderTypeQuote Model
 *
 * @method \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeQuote getResource()
 * @method \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeQuote\Collection getCollection()
 */
class OrderTypeQuote extends AbstractModel implements OrderTypeQuoteInterface, IdentityInterface
{
    const CACHE_TAG = 'processeightadminhtmlexamples_aftcop_ordertypequote';

    /**#@+
     * Order Types
     */
    const ORDER_TYPE_ORDER = 1;
    const ORDER_TYPE_COLLECTION = 2;
    const ORDER_TYPE_REPLACEMENTS = 3;

    /**#@-*/

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'processeightadminhtmlexamples_aftcop_ordertypequote';

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeQuote::class);
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Allowed order types.
     *
     * @return array
     */
    public function getOrderTypes()
    {
        return [
            self::ORDER_TYPE_ORDER        => __('Order'),
            self::ORDER_TYPE_COLLECTION   => __('Collection'),
            self::ORDER_TYPE_REPLACEMENTS => __('Replacements'),
        ];
    }

    /**
     * Returns the quote ID.
     *
     * @return int Quote ID.
     */
    public function getQuoteId() : int
    {
        return $this->_getData(self::KEY_QUOTE_ID);
    }

    /**
     * Sets the quote ID.
     *
     * @param int $quoteId
     *
     * @return $this
     */
    public function setQuoteId(int $quoteId)
    {
        return $this->setData(self::KEY_QUOTE_ID, $quoteId);
    }

    /**
     * Returns the order type ID.
     *
     * @return int Order type ID
     */
    public function getOrderTypeId()
    {
        return $this->_getData(self::KEY_ORDER_TYPE_ID);
    }

    /**
     * Sets the order type ID.
     *
     * @param int $orderTypeId
     *
     * @return $this
     */
    public function setOrderTypeId(int $orderTypeId)
    {
        return $this->setData(self::KEY_ORDER_TYPE_ID, $orderTypeId);
    }

    /**
     * Returns the reference order increment ID.
     *
     * @return string Reference order increment ID.
     */
    public function getReferenceOrderIncrementId() : string
    {
        return $this->_getData(self::KEY_REFERENCE_ORDER_INCREMENT_ID);
    }

    /**
     * Sets the reference order increment ID.
     *
     * @param string $referenceOrderIncrementId
     *
     * @return $this
     */
    public function setReferenceOrderIncrementId(string $referenceOrderIncrementId)
    {
        return $this->setData(self::KEY_REFERENCE_ORDER_INCREMENT_ID, $referenceOrderIncrementId);
    }

    /**
     * Returns the cart creation date and time.
     *
     * @return string|null Cart creation date and time. Otherwise, null.
     */
    public function getCreatedAt() : ?string
    {
        return $this->_getData(self::KEY_CREATED_AT);
    }

    /**
     * Sets the cart creation date and time.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(string $createdAt)
    {
        return $this->setData(self::KEY_CREATED_AT, $createdAt);
    }

    /**
     * Returns the cart last update date and time.
     *
     * @return string|null Cart last update date and time. Otherwise, null.
     */
    public function getUpdatedAt() : ?string
    {
        return $this->_getData(self::KEY_UPDATED_AT);
    }

    /**
     * Sets the cart last update date and time.
     *
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt)
    {
        return $this->setData(self::KEY_UPDATED_AT, $updatedAt);
    }
}