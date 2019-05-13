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

namespace ProcessEight\ExtensionAttributesExample\Model\ExternalLinks;

use ProcessEight\ExtensionAttributesExample\Api\ExternalLinksProviderInterface;

/**
 * Class Provider
 *
 * Loads the 'external links' for a particular product and returns them as an array
 *
 * @package ProcessEight\ExtensionAttributesExample\Model\ExternalLinks
 */
class Provider implements ExternalLinksProviderInterface
{
    /** @var  \Magento\Framework\EntityManager\EntityManager */
    private $entityManager;

    /** @var  \ProcessEight\ExtensionAttributesExample\Model\ResourceModel\ExternalLinks\Loader */
    private $loader;

    /** @var  \ProcessEight\ExtensionAttributesExample\Model\ExternalLinkFactory */
    private $externalLinkFactory;

    /**
     * Provider constructor.
     *
     * @param \Magento\Framework\EntityManager\EntityManager                                    $entityManager
     * @param \ProcessEight\ExtensionAttributesExample\Model\ResourceModel\ExternalLinks\Loader $loader
     * @param \ProcessEight\ExtensionAttributesExample\Model\ExternalLinkFactory                $externalLinkFactory
     */
    public function __construct
    (
        \Magento\Framework\EntityManager\EntityManager $entityManager,
        \ProcessEight\ExtensionAttributesExample\Model\ResourceModel\ExternalLinks\Loader $loader,
        \ProcessEight\ExtensionAttributesExample\Model\ExternalLinkFactory $externalLinkFactory
    ) {
        $this->entityManager       = $entityManager;
        $this->loader              = $loader;
        $this->externalLinkFactory = $externalLinkFactory;
    }

    /**
     * Get array of loaded external links
     *
     * @param int $productId
     *
     * @return array
     * @throws \Exception
     */
    public function getLinks($productId)
    {
        $externalLinks = [];
        $ids           = $this->loader->getIdsByProductId($productId);

        foreach ($ids as $id) {
            $externalLink    = $this->externalLinkFactory->create();
            $externalLinks[] = $this->entityManager->load($externalLink, $id);
        }

        return $externalLinks;
    }
}
