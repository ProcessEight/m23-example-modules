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

namespace DevCertUnitOne\OneFour\Model;

class Currency
{

    /**
     * @var \Magento\Directory\Model\Currency\Import\ImportInterface
     */
    private $import;

    /**
     * Currency constructor.
     *
     * @param \Magento\Directory\Model\Currency\Import\ImportInterface $import
     */
    public function __construct(
        \Magento\Directory\Model\Currency\Import\ImportInterface $import
    ) {
        $this->import = $import;

        // Normally, doing anything other than assigning properties is against the M2TG
        // We do this here only to show how using Proxies can help avoid loading
        // computationally expensive classes unnecessarily
        $this->import->fetchRates();
    }

    /**
     * Fetch rates from remote service
     *
     * @return array
     */
    public function fetchRates() : array
    {
        return $this->import->fetchRates();
    }
}
