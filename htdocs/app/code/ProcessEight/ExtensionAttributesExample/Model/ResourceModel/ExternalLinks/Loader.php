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

namespace ProcessEight\ExtensionAttributesExample\Model\ResourceModel\ExternalLinks;

use ProcessEight\ExtensionAttributesExample\Api\Data\ExternalLinkInterface;

/**
 * Class Loader
 *
 * Queries database for all 'external link' IDs related to a particular product ID, then returns them as an array
 *
 * @package ProcessEight\ExtensionAttributesExample\Model\ExternalLinks
 */
class Loader
{
    /** @var  \Magento\Framework\EntityManager\MetadataPool */
    private $metadataPool;

    /** @var  \Magento\Framework\App\ResourceConnection\ */
    private $resourceConnection;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\EntityManager\MetadataPool $metadataPool
     * @param \Magento\Framework\App\ResourceConnection     $resourceConnection
     */
    public function __construct(
        \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->metadataPool       = $metadataPool;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Query database for external links linked to $productId
     *
     * @param $productId
     *
     * @return array
     * @throws \Exception
     */
    public function getIdsByProductId($productId) : array
    {
        $metadata   = $this->metadataPool->getMetadata(ExternalLinkInterface::class);
        $connection = $this->resourceConnection->getConnection();

        $select = $connection
            ->select()
            ->from($metadata->getEntityTable(), ExternalLinkInterface::LINK_ID)
            ->where(ExternalLinkInterface::PRODUCT_ID . ' = ?', $productId);
        $ids    = $connection->fetchCol($select);

        return $ids ?: [];
    }
}
