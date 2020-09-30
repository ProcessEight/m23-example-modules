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

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 *
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $tableName = $installer->getTable('processeightadminhtmlexamples_aftcop_quote_to_order_type');

        if (!$installer->getConnection()->isTableExists($tableName)) {
            $table = $installer->getConnection()
                               ->newTable($tableName)
                               ->addColumn(
                                   'quote_to_order_type_id',
                                   Table::TYPE_INTEGER,
                                   null,
                                   ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                                   'Primary Key ID'
                               )
                               ->addColumn(
                                   'quote_id',
                                   Table::TYPE_INTEGER,
                                   10,
                                   ['nullable' => true, 'unsigned' => true],
                                   'Quote ID'
                               )
                               ->addColumn(
                                   'order_id',
                                   \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                                   10,
                                   [],
                                   'Order ID'
                               )
                               ->addColumn(
                                   'order_type_id',
                                   \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                                   null,
                                   ['nullable' => false],
                                   'Order Type ID'
                               )
                               ->addIndex(
                                   $setup->getIdxName($tableName, ['order_type_id']),
                                   ['order_type_id']
                               )
                               ->addForeignKey(
                                   $setup->getFkName(
                                       $tableName,
                                       'quote_id',
                                       'quote',
                                       'entity_id'
                                   ),
                                   'quote_id',
                                   $setup->getTable('quote'),
                                   'entity_id',
                                   \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                               )
                               ->setComment('Quote to Order Type associative table');

            $installer->getConnection()->createTable($table);
        }

        $tableName = $installer->getTable('processeightadminhtmlexamples_aftcop_order_to_order_type');

        if (!$installer->getConnection()->isTableExists($tableName)) {
            $table = $installer->getConnection()
                               ->newTable($tableName)
                               ->addColumn(
                                   'order_to_order_type_id',
                                   Table::TYPE_INTEGER,
                                   null,
                                   ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                                   'Primary Key ID'
                               )
                               ->addColumn(
                                   'quote_id',
                                   Table::TYPE_INTEGER,
                                   10,
                                   ['nullable' => true, 'unsigned' => true],
                                   'Quote ID'
                               )
                               ->addColumn(
                                   'order_id',
                                   \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                                   10,
                                   [],
                                   'Order ID'
                               )
                               ->addColumn(
                                   'order_type_id',
                                   \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                                   null,
                                   ['nullable' => false],
                                   'Order Type ID'
                               )
                               ->addIndex(
                                   $setup->getIdxName($tableName, ['order_type_id']),
                                   ['order_type_id']
                               )
                               ->addForeignKey(
                                   $setup->getFkName(
                                       $tableName,
                                       'quote_id',
                                       'quote',
                                       'entity_id'
                                   ),
                                   'quote_id',
                                   $setup->getTable('quote'),
                                   'entity_id',
                                   \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                               )
                               ->setComment('Order to Order Type associative table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
