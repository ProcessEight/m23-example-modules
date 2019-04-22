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

namespace DevCertUnitOne\OneOne\Plugin\Magento\Dhl\Model;

class Carrier
{
    /**
     * Log the parameters
     *
     * @param \Magento\Dhl\Model\Carrier    $subject The targeted class
     * @param \Magento\Framework\DataObject $request Parameter(s) of the targeted method
     *
     * @return null
     */
    public function beforeSetRequest(
        \Magento\Dhl\Model\Carrier $subject,    // The target class
        \Magento\Framework\DataObject $request  // Parameter(s) of the targeted method
    )
    {
        // If the method does not change the argument for the observed method, it should return null.
        return null;
    }

    /**
     * Log the parameters
     *
     * @param \Magento\Dhl\Model\Carrier    $subject The targeted class
     * @param \Magento\Dhl\Model\Carrier    $result  Result of the targeted method
     * @param \Magento\Framework\DataObject $request Parameter(s) of the targeted method
     *
     * @return mixed
     */
    public function afterSetRequest(
        \Magento\Dhl\Model\Carrier $subject,    // The target class
        $result,                                // Result of the targeted method
        \Magento\Framework\DataObject $request  // Parameter(s) of the targeted method
    )
    {
        return $result;
    }
}
