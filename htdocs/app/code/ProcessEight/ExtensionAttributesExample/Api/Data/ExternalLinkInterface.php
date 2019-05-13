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

namespace ProcessEight\ExtensionAttributesExample\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ExternalLinkInterface extends ExtensibleDataInterface
{
    const TYPE = "type";

    const LINK = "link";

    const PRODUCT_ID = "product_id";

    const LINK_ID = "link_id";

    /**
     * Retrieve Link Type
     *
     * @return string
     */
    public function getLinkType();

    /**
     * Set Link Type
     *
     * @param string $type
     *
     * @return self
     */
    public function setLinkType($type);

    /**
     * Retrieve Provider link
     *
     * @return string
     */
    public function getLink();

    /**
     * Set Provider link
     *
     * @param string $link
     *
     * @return self
     */
    public function setLink($link);

    /**
     * Set Product Id for further updates
     *
     * @param int $id
     *
     * @return self
     */
    public function setProductId($id);

    /**
     * Retrieve product id
     *
     * @return int
     */
    public function getProductId();

    /**
     * @return int
     */
    public function getLinkId();

    /**
     * @param int $linkId
     *
     * @return self
     */
    public function setLinkId($linkId);

    /**
     * @return \ProcessEight\ExtensionAttributesExample\Api\Data\ExternalLinkExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \ProcessEight\ExtensionAttributesExample\Api\Data\ExternalLinkExtensionInterface $extensionAttributes
     *
     * @return self
     */
    public function setExtensionAttributes(
        \ProcessEight\ExtensionAttributesExample\Api\Data\ExternalLinkExtensionInterface $extensionAttributes
    );
}
