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

namespace ProcessEight\ExtensionAttributesExample\Model\Plugin\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use ProcessEight\ExtensionAttributesExample\Api\Data\ExternalLinkInterface;

/**
 * Class Repository
 *
 * Manages persistence operations for 'existing links'
 *
 * @package ProcessEight\ExtensionAttributesExample\Model\Plugin\Product
 */
class Repository
{
    /** @var \Magento\Catalog\Api\Data\ProductExtensionFactory */
    private $productExtensionFactory;

    /** @var ProductInterface */
    private $currentProduct;

    /** @var  \Magento\Framework\EntityManager\EntityManager */
    private $entityManager;

    /** @var \ProcessEight\ExtensionAttributesExample\Api\ExternalLinksProviderInterface */
    private $externalLinksProvider;

    /**
     * Constructor.
     *
     * @param \Magento\Catalog\Api\Data\ProductExtensionFactory                           $productExtensionFactory
     * @param \Magento\Framework\EntityManager\EntityManager                              $entityManager
     * @param \ProcessEight\ExtensionAttributesExample\Api\ExternalLinksProviderInterface $externalLinksProvider
     */
    public function __construct(
        \Magento\Catalog\Api\Data\ProductExtensionFactory $productExtensionFactory,
        \Magento\Framework\EntityManager\EntityManager $entityManager,
        \ProcessEight\ExtensionAttributesExample\Api\ExternalLinksProviderInterface $externalLinksProvider
    ) {
        $this->productExtensionFactory = $productExtensionFactory;
        $this->entityManager           = $entityManager;
        $this->externalLinksProvider   = $externalLinksProvider;
    }

    /**
     * Add external links to product extension attributes
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Magento\Framework\Api\SearchResults            $searchResult
     *
     * @return \Magento\Framework\Api\SearchResults
     */
    public function afterGetList
    (
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Framework\Api\SearchResults $searchResult
    ) {
        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        foreach ($searchResult->getItems() as $product) {
            $this->addExternalLinksToProduct($product);
        }

        return $searchResult;
    }

    /**
     * Prepare external links for saving
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param ProductInterface                                $product
     *
     * @return void
     */
    public function beforeSave
    (
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Catalog\Api\Data\ProductInterface $product
    ) {
        $this->currentProduct = $product;
    }

    /**
     * Assign external links to product on get
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Magento\Catalog\Api\Data\ProductInterface      $product
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function afterGet
    (
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Catalog\Api\Data\ProductInterface $product
    ) {
        $this->addExternalLinksToProduct($product);

        return $product;
    }

    /**
     * Compare old and new links. And if old links has the same as new one -> delete them
     *
     * @param array $newLinks
     * @param array $oldLinks
     *
     * @throws \Exception
     */
    private function cleanOldLinks(array $newLinks, array $oldLinks)
    {
        /** @var ExternalLinkInterface $link */
        foreach ($newLinks as $link) {
            /** @var ExternalLinkInterface $oldLink */
            foreach ($oldLinks as $oldLink) {
                if ($oldLink->getLinkType() === $link->getLinkType()) {
                    $this->entityManager->delete($oldLink);
                }
            }
        }
    }

    /**
     * Check to see if any new extension links (which may have been added) need to be saved
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Magento\Catalog\Api\Data\ProductInterface      $product
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Exception
     */
    public function afterSave
    (
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Catalog\Api\Data\ProductInterface $product
    ) {
        if ($this->currentProduct !== null) {
            /** @var ProductInterface $previosProduct */
            $extensionAttributes = $this->currentProduct->getExtensionAttributes();

            if ($extensionAttributes && $extensionAttributes->getExternalLinks()) {
                /** @var ExternalLinkInterface $externalLink */
                $externalLinks    = $extensionAttributes->getExternalLinks();
                $oldExternalLinks = $product->getExtensionAttributes()->getExternalLinks();

                if (is_array($externalLinks)) {
                    $this->cleanOldLinks($externalLinks, $oldExternalLinks);
                    /** @var ExternalLinkInterface $link */
                    foreach ($externalLinks as $link) {
                        $link->setProductId($product->getId());
                        $this->entityManager->save($link);
                    }
                }
            }

            $this->currentProduct = null;
        }

        return $product;
    }

    /**
     * Get the loaded external links and assign to the products' extension attributes property
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     *
     * @return \ProcessEight\ExtensionAttributesExample\Model\Plugin\Product\Repository
     */
    private function addExternalLinksToProduct(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $extensionAttributes = $product->getExtensionAttributes();

        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->productExtensionFactory->create();
        }
        $externalLinks = $this->externalLinksProvider->getLinks($product->getId());
        $extensionAttributes->setExternalLinks($externalLinks);
        $product->setExtensionAttributes($extensionAttributes);

        return $this;
    }
}
