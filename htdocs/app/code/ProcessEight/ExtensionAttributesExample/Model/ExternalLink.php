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

namespace ProcessEight\ExtensionAttributesExample\Model;

/**
 * Class ExternalLink
 *
 * Defines an external link, i.e. duckduckgo.com
 *
 * @package ProcessEight\ExtensionAttributesExample\Model
 */
final class ExternalLink implements \ProcessEight\ExtensionAttributesExample\Api\Data\ExternalLinkInterface
{
    /** @var  array */
    private $link;

    /** @var  int */
    private $linkId;

    /** @var  int */
    private $productId;

    /** @var  string */
    private $linkType;

    /** @var  array */
    private $extensionAttributes;

    /**
     * @inheritdoc
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @inheritdoc
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLinkType()
    {
        return $this->linkType;
    }

    /**
     * @inheritdoc
     */
    public function setLinkType($type)
    {
        $this->linkType = $type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * @inheritdoc
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;

        return $this->linkId;
    }

    /**
     * @inheritdoc
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @inheritdoc
     */
    public function setProductId($id)
    {
        $this->productId = $id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(
        \ProcessEight\ExtensionAttributesExample\Api\Data\ExternalLinkExtensionInterface $extensionAttributes
    ) {
        $this->extensionAttributes = $extensionAttributes;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->extensionAttributes;
    }
}
