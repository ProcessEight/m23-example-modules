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

namespace DevCertUnitOne\OneThree\Config;

use Magento\Framework\Config\Dom\ValidationSchemaException;

/**
 * Class Converter
 *
 * Converts a Config XML file into a multi-dimensional PHP array
 */
class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * Node type wrapper for warehouse nodes
     *
     * @var string
     */
    private static $warehouse = 'warehouse';

    /**
     * Node type for warehouse name
     *
     * @var string
     */
    private static $warehouseName = 'name';

    /**
     * Node type for warehouse postcode
     *
     * @var string
     */
    private static $warehousePostcode = 'postcode';

    /**
     * Converts the warehouses_list.xml config XML file into a multi-dimensional array
     *
     * @param \DOMDocument $source
     *
     * @return array
     * @throws ValidationSchemaException
     */
    public function convert($source)
    {
        $warehouses     = $source->getElementsByTagName(self::$warehouse);
        foreach ($warehouses as $warehouse) {
            $warehousesList['warehouses_list'][] = $this->getWarehouseData($warehouse);
        }

        return $warehousesList ?? [];
    }

    /**
     * Returns the data of a single <warehouse> node of warehouses_list.xml as a multi-dimensional array
     *
     * @param \DOMElement $element
     *
     * @return array
     * @throws ValidationSchemaException
     */
    private function getWarehouseData(\DOMElement $element)
    {
        $warehouseName     = $this->readSubnodeValue($element, self::$warehouseName);
        $warehousePostcode = $this->readSubnodeValue($element, self::$warehousePostcode);

        return [
            self::$warehouseName => $warehouseName,
            self::$warehousePostcode => $warehousePostcode
        ];
    }

    /**
     * Reads node value by node type
     *
     * @param \DOMElement $element
     * @param string      $subNodeType
     *
     * @return mixed
     * @throws ValidationSchemaException
     */
    private function readSubnodeValue(\DOMElement $element, $subNodeType)
    {
        $domList = $element->getElementsByTagName($subNodeType);
        if (empty($domList[0])) {
            throw new ValidationSchemaException(__('Only single instance of "%1" node is required.', $subNodeType));
        }

        $subNodeValue = trim($domList[0]->nodeValue);
        if (!$subNodeValue) {
            throw new ValidationSchemaException(__('Value for "%1" node is required.', $subNodeType));
        }

        return $subNodeValue;
    }
}
