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
 * Class ValidateVendorNameStage
 *
 * Verifies that the Vendor Name meets the pre-defined criteria
 *
 * - First iteration of pipeline will define the data this stage needs
 * - Second iteration of pipeline will execute the logic in the stage
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class ValidateVendorNameStage extends BaseStage
{
    const VENDOR_NAME_REGEX_PATTERN = '/[A-Z]+[A-Za-z0-9]{1,}/';

    /**
     * @param array $payload
     *
     * @return array
     */
    public function configureStage(array $payload) : array
    {
        $payload['config'][get_class($this)]['options'][ConfigKey::VENDOR_NAME] = [
            'name'                    => ConfigKey::VENDOR_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'description'             => 'Vendor name',
            'question'                => '<question>Vendor name [ProcessEight]: </question> ',
            'question_default_answer' => 'ProcessEight',
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
        $vendorName = $payload['config'][get_class($this)]['values'][ConfigKey::VENDOR_NAME];

        if ($payload['is_valid'] === false
            || !isset($vendorName)
            || empty($vendorName)
            || preg_match(self::VENDOR_NAME_REGEX_PATTERN, $vendorName) !== 1
        ) {
            $payload['is_valid']   = false;
            $payload['messages'][] = 'Invalid vendor name';
        } else {
            $payload['messages'][] = 'Vendor name "' . $vendorName . '" passed validation';
        }

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}
