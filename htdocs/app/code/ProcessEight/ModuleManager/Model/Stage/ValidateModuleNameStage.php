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

/**
 * Class ValidateModuleNameStage
 *
 * Verifies that the Module Name meets the pre-defined criteria
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class ValidateModuleNameStage extends BaseStage
{
    const MODULE_NAME_REGEX_PATTERN = '/[A-Z]+[A-Z0-9a-z]{1,}/';

    /**
     * @param array $payload
     *
     * @return array
     */
    public function processStage(array $payload) : array
    {
        $moduleName = $payload['config']['validate-module-name-stage'][ConfigKey::MODULE_NAME];

        if ($payload['is_valid'] === false
            || !isset($moduleName)
            || empty($moduleName)
            || preg_match(self::MODULE_NAME_REGEX_PATTERN, $moduleName) !== 1
        ) {
            $payload['is_valid']   = false;
            $payload['messages'][] = __METHOD__ . ': Invalid module name';
        }

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}
