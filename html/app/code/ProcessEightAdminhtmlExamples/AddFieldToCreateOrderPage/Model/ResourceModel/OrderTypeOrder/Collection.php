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

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeOrder;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Primary Key name
     *
     * @var string
     */
    protected $_idFieldName = 'order_to_order_type_id';

    protected function _construct()
    {
        $this->_init(
            \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeOrder::class,
            \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel\OrderTypeOrder::class
        );
    }

}
