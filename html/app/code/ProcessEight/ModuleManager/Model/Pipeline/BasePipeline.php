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
 * @copyright   Copyright (c) 2020 ProcessEight
 * @author      ProcessEight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Model\Pipeline;

class BasePipeline
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    public $pipeline;

    /**
     * Constructor
     *
     * @param \League\Pipeline\Pipeline $pipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline
    ) {
        $this->pipeline = $pipeline;
    }

    /**
     * Called when this Pipeline is invoked by another Pipeline/Stage
     *
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function __invoke(array $payload) : array
    {
        if ($payload['is_valid'] === true) {
            $payload = $this->processPipeline($payload);
        }

        return $payload;
    }

    /**
     * Define and configure the Stages in this Pipeline, then execute it
     *
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function processPipeline(array $payload) : array
    {
        // (Example) Add the Pipelines/Stages we need for this command
        $pipeline = $this->pipeline
            // Validate the module name
//            ->pipe($this->validateModuleNamePipeline)
            // Create the module folder
//            ->pipe($this->createModuleFolderStage)
            // Create the etc folder
//            ->pipe($this->createEtcFolderStage)
            // Create the module.xml
//            ->pipe($this->createXmlFileStage)
            // Create the composer.json
//            ->pipe($this->createComposerJsonFileStage)
            // Create the registration.php
//            ->pipe($this->createRegistrationPhpFileStage)
        ;

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
