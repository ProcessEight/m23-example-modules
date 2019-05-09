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

namespace ProcessEight\ModuleManager\Model\Stage;

/**
 * Class ValidateVendorNameStage
 *
 * Verifies that the Vendor Name meets the pre-defined criteria
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class ValidateVendorNameStage
{
    const VENDOR_NAME_REGEX_PATTERN = '/[A-Z]+[A-Za-z0-9]{1,}/';

    /**
     * @var string
     */
    private $vendorName;

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function __invoke(array $payload) : array
    {
        $vendorName = $this->getVendorName();
        if ($payload['is_valid'] === false
            || !isset($vendorName)
            || empty($vendorName)
            || preg_match(self::VENDOR_NAME_REGEX_PATTERN, $vendorName) !== 1
        ) {
            $payload['is_valid']           = false;
            $payload['validation_message'] = 'Invalid vendor name';
        }

        // Pass payload onto next stage/pipeline
        return $payload;
    }

    /**
     * @param string $vendorName
     *
     * @return void
     */
    public function setVendorName(string $vendorName) : void
    {
        $this->vendorName = $vendorName;
    }

    /**
     * @return string
     */
    public function getVendorName() : string
    {
        return $this->vendorName;
    }
}
