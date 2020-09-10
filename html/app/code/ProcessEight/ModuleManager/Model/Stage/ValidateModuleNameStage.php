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

    public $id = 'validateModuleNameStage';

    /**
     * @param array $payload
     *
     * @return array
     */
    public function configureStage(array $payload) : array
    {
        // Ask the user for the module name, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::MODULE_NAME] = [
            'name'        => ConfigKey::MODULE_NAME,
            'shortcut'    => null,
            'mode'        => \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'description' => 'Module name',
            'question' => '<question>Module name [Test]: </question> ',
            'question_default_answer' => 'Test',
        ];

        return $payload;
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    public function processStage(array $payload) : array
    {
        $moduleName = $payload['config'][$this->id]['values'][ConfigKey::MODULE_NAME];

        if ($payload['is_valid'] === false
            || !isset($moduleName)
            || empty($moduleName)
            || preg_match(self::MODULE_NAME_REGEX_PATTERN, $moduleName) !== 1
        ) {
            $payload['is_valid']   = false;
            $payload['messages'][] = 'Invalid module name. Module name should match regex ' . self::MODULE_NAME_REGEX_PATTERN;
        } else {
            $payload['messages'][] = 'Module name "' . $moduleName . '" passed validation';
        }

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}
