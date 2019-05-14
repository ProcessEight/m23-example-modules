<?php /** @noinspection PhpUndefinedClassInspection */
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

namespace ProcessEight\ExtensionAttributesExample\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExtensionAttributesExample extends Command
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    private $searchCriteria;

    /**
     * CustomLoggerExample constructor.
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Api\SearchCriteriaInterface  $searchCriteria
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    ) {
        parent::__construct();

        $this->productRepository = $productRepository;
        $this->searchCriteria    = $searchCriteria;
    }

    protected function configure()
    {
        $this->setName("process-eight:example:extension-attributes");
        $this->setDescription("Demonstration of how to use Extension Attributes in Magento 2.");
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $products = $this->productRepository->getList($this->searchCriteria);

        foreach ($products->getItems() as $product) {
            $extensionAttributes = $product->getExtensionAttributes();

            $output->writeln("SKU: " . $product->getSku());
            $output->writeln("ID: " . $product->getId());

            foreach ($extensionAttributes->__toArray() as $attributeCode => $extensionAttribute) {
                $output->writeln("Type: " . gettype($extensionAttribute));
                $data = is_object($extensionAttribute) ? $extensionAttribute->getData() : $extensionAttribute;
                $output->writeln("{$attributeCode}: " . print_r($data, true));
            }
            break;
        }

        $output->writeln("All done!");
    }
}
