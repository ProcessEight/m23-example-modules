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
     * Called when this pipeline is invoked by another pipeline/stage (as opposed to being inject by DI)
     *
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function __invoke(array $payload) : array
    {
        if ($payload['is_valid'] === true) {
            $payload = $this->processStage($payload);
        }

        return $payload;
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    public function processStage(array $payload) : array
    {
        if ($payload['is_valid'] === false
            || !isset($payload['config']['validate-vendor-name-stage']['vendor-name'])
            || empty($payload['config']['validate-vendor-name-stage']['vendor-name'])
            || preg_match(self::VENDOR_NAME_REGEX_PATTERN, $payload['config']['validate-vendor-name-stage']['vendor-name']) !== 1
        ) {
            $payload['is_valid']           = false;
            $payload['validation_message'] = 'Invalid vendor name';
        }

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}