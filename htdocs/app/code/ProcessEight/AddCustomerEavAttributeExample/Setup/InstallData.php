<?php
/**
 * Process Eight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 Process Eight
 * @author      Process Eight
 *
 */

declare(strict_types=1);

namespace ProcessEight\AddCustomerEavAttributeExample\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    const NICKNAME_ATTRIBUTE_CODE = 'processeight_nickname';

    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * Creates a new attribute 'processeight_nickname' and adds it to the 'customer' entity and `adminhtml_customer' form
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // None of these are required. If none are set, Magento will set them to safe default values in
        // \Magento\Eav\Model\Entity\Setup\PropertyMapper for all entities (there are other mappers for other entities as well)
        $eavEntityCommonProperties = [
            'label'           => 'Nickname 1540 ' . date('His'),
            'required'        => 0,
//            'user_defined'    => 1,
//            'type'            => 'varchar', // static, varchar, int, text, datetime, decimal
//            'backend'         => null,
//            'table'           => null,
//            'frontend'        => null,
//            'input'           => 'text',    // select, text, date, hidden, boolean, multiline, textarea, image, multiselect, price, weight, media_image, gallery
//            'frontend_class'  => null,
//            'source'          => null,
//            'default'         => null,
//            'unique'          => 0,
//            'note'            => null,
//            'global'          => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
//            'position'        => 10,
//            'group'           => 'Account Information', // Label of tab the attribute appears in
        ];

        // Only the 'system' property is required.
        // If none of the others are set, Magento will set them to safe default values in
        // \Magento\Customer\Model\ResourceModel\Setup\PropertyMapper (for customer entities).
        // There are other mappers for other entities as well.
        $customerEntitySpecificProperties = [
            'system'          => 0,
            'visible'         => 1,
//            'data_model'      => null,
//            'input_filter'    => null,
//            'multiline_count' => 0,
//            'sort_order'      => 10,
//            'validate_rules'  => null,
            // These options control the behaviour of the attribute in admin grids
//            'is_used_in_grid'       => true,
//            'is_filterable_in_grid' => true,
//            'is_searchable_in_grid' => true,
        ];

        $data = array_merge($eavEntityCommonProperties, $customerEntitySpecificProperties);

        /** @var \Magento\Customer\Setup\CustomerSetup $customerSetup */
        $customerSetup  = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(Customer::ENTITY, self::NICKNAME_ATTRIBUTE_CODE, $data);
    }
}
