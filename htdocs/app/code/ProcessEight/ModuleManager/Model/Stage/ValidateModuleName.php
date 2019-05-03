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

class ValidateModuleName
{
    const MODULE_NAME = 'module-name';
    const MODULE_NAME_REGEX_PATTERN = '/[A-Z]+[A-Z0-9a-z]{1,}/';

    /**
     * @param mixed[] $config
     *
     * @return mixed[]
     */
    public function __invoke(array $config) : array
    {
        if ($config['is_valid'] === false
            || !isset($config['data'][self::MODULE_NAME])
            || empty($config['data'][self::MODULE_NAME])
            || preg_match(self::MODULE_NAME_REGEX_PATTERN, $config['data'][self::MODULE_NAME]) !== 1
        ) {
            $config['is_valid'] = false;
            $config['validation_message'] = 'Invalid module name';
        }

        return $config;
    }
}
