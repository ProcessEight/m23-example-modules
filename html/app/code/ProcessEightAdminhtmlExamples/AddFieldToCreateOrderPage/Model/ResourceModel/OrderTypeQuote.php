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

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\ResourceModel;

class OrderTypeQuote extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialise table and Primary Key name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('processeightadminhtmlexamples_aftcop_quote_to_order_type', 'quote_to_order_type_id');
    }

}
