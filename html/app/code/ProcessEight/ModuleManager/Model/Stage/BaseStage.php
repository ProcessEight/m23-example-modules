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

/**
 * Class BaseStage
 *
 * Encapsulates common logic for stages
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class BaseStage
{
    /**
     * Called when this pipeline is invoked by another pipeline/stage (as opposed to being injected by DI)
     *
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function __invoke(array $payload) : array
    {
        if ($payload['is_valid'] === true && $payload['mode'] === 'configure') {
            /**
             * Hotfix: This adds every stage to payload[config]. The reason was to avoid calling {{stage}}->configure() in every stage, even if there was nothing to configure
             * This was also necessary because the payload processing in {{command}}->execute would skip stages which didn't call {{stage}}->configure()
             */
            $payload['config'] = array_merge($payload['config'], [get_called_class() => []]);
            $payload = $this->configureStage($payload);
        }
        if ($payload['is_valid'] === true && $payload['mode'] === 'process') {
            $payload = $this->processStage($payload);
        }

        // Pass payload onto next stage/pipeline
        return $payload;
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    public function configureStage(array $payload) : array
    {
        // Override this method to add stage-specific data configuration
        return $payload;
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    public function processStage(array $payload) : array
    {
        return $payload;
    }
}
