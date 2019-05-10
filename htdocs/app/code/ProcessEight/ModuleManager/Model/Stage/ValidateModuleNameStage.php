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
 * Class ValidateModuleNameStage
 *
 * Verifies that the Module Name meets the pre-defined criteria
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class ValidateModuleNameStage
{
    const MODULE_NAME_REGEX_PATTERN = '/[A-Z]+[A-Z0-9a-z]{1,}/';

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
            || !isset($payload['config']['validate-module-name-stage']['module-name'])
            || empty($payload['config']['validate-module-name-stage']['module-name'])
            || preg_match(self::MODULE_NAME_REGEX_PATTERN, $payload['config']['validate-module-name-stage']['module-name']) !== 1
        ) {
            $payload['is_valid']           = false;
            $payload['messages'][] = __METHOD__ . ': Invalid module name';
        }

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}
