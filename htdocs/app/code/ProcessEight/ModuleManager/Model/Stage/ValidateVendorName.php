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
use ProcessEight\ModuleManager\Model\ConfigKey;

class ValidateVendorName
{
    const VENDOR_NAME_REGEX_PATTERN = '/[A-Z]+[A-Za-z0-9]{1,}/';

    /**
     * @param mixed[] $config
     *
     * @return mixed[]
     */
    public function __invoke(array $config) : array
    {
        if ($config['is_valid'] === false
            || !isset($config['data'][ConfigKey::VENDOR_NAME])
            || empty($config['data'][ConfigKey::VENDOR_NAME])
            || preg_match(self::VENDOR_NAME_REGEX_PATTERN, $config['data'][ConfigKey::VENDOR_NAME]) !== 1
        ) {
            $config['is_valid'] = false;
            $config['validation_message'] = 'Invalid vendor name';
        }

        return $config;
    }
}
