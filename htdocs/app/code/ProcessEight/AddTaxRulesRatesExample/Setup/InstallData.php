<?php
/**
 * ProcessEight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 ProcessEight
 * @author      ProcessEight
 *
 */

declare(strict_types=1);

namespace ProcessEight\AddTaxRulesRatesExample\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Tax\Api\TaxRateRepositoryInterface
     */
    private $taxRateRepository;
    /**
     * @var \Magento\Tax\Api\Data\TaxRateInterfaceFactory
     */
    private $taxRateFactory;
    /**
     * @var \Magento\Tax\Api\TaxRuleRepositoryInterface
     */
    private $taxRuleRepository;
    /**
     * @var \Magento\Tax\Api\Data\TaxRuleInterfaceFactory
     */
    private $taxRuleInterfaceFactory;
    /**
     * @var \Magento\Tax\Api\TaxClassRepositoryInterface
     */
    private $taxClassRepository;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * Constructor
     *
     * @param \Magento\Tax\Api\TaxRateRepositoryInterface   $taxRateRepository
     * @param \Magento\Tax\Api\Data\TaxRateInterfaceFactory $taxRateFactory
     * @param \Magento\Tax\Api\TaxRuleRepositoryInterface   $taxRuleRepository
     * @param \Magento\Tax\Api\Data\TaxRuleInterfaceFactory $taxRuleInterfaceFactory
     * @param \Magento\Tax\Api\TaxClassRepositoryInterface  $taxClassRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder  $searchCriteriaBuilder
     */
    public function __construct(
        \Magento\Tax\Api\TaxRateRepositoryInterface $taxRateRepository,
        \Magento\Tax\Api\Data\TaxRateInterfaceFactory $taxRateFactory,
        \Magento\Tax\Api\TaxRuleRepositoryInterface $taxRuleRepository,
        \Magento\Tax\Api\Data\TaxRuleInterfaceFactory $taxRuleInterfaceFactory,
        \Magento\Tax\Api\TaxClassRepositoryInterface $taxClassRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->taxRateRepository       = $taxRateRepository;
        $this->taxRateFactory          = $taxRateFactory;
        $this->taxRuleRepository       = $taxRuleRepository;
        $this->taxRuleInterfaceFactory = $taxRuleInterfaceFactory;
        $this->taxClassRepository      = $taxClassRepository;
        $this->searchCriteriaBuilder   = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Tax\Model\Calculation\Rate $taxRate */
        $taxRate = $this->taxRateFactory->create([
            'data' => [
                'tax_country_id' => 'GB',
                'tax_region_id'  => 0, // Displayed as '*' in the admin
                'tax_postcode'   => '*',
                'rate'           => 21.0000,
                'code'           => 'UK-*-*-Standard Rate',
            ],
        ]);
        $taxRate->setDataChanges(true);

        $this->taxRateRepository->save($taxRate);

        // Get the customer tax class(es)
        $customerTaxClassSearchCriteria = $this->searchCriteriaBuilder->addFilter('class_name', 'Retail Customer')
                                                                      ->create();
        $customerTaxClasses               = $this->taxClassRepository->getList($customerTaxClassSearchCriteria)
                                                                   ->getItems();
        // Get the product tax class(es)
        $productTaxClassSearchCriteria = $this->searchCriteriaBuilder->addFilter('class_name', 'Taxable Goods')
                                                                     ->create();
        $productTaxClasses               = $this->taxClassRepository->getList($productTaxClassSearchCriteria)
                                                                  ->getItems();

        if ($customerTaxClasses && $productTaxClasses) {
            $taxRule = $this->taxRuleInterfaceFactory->create([
                'data' => [
                    'code'                   => 'UK Standard Rate',
                    'priority'               => 0,
                    'position'               => 0,
                    'customer_tax_class_ids' => [
                        array_keys($customerTaxClasses)[0],
                    ],
                    'product_tax_class_ids'  => [
                        array_keys($productTaxClasses)[0],
                    ],
                    'tax_rate_ids'           => [
                        $taxRate->getId(),
                    ],
                ],
            ]);
            $taxRule->setDataChanges(true);

            $this->taxRuleRepository->save($taxRule);
        }
    }
}
