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

namespace ProcessEight\ModuleManager\Model\Pipeline;

/**
 * Class CreateCommandFolderPipeline
 *
 * This pipeline:
 * - Calls the ValidateModuleNamePipeline
 * - Creates the folder
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateCommandFolderPipeline
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateCommandFolderStage
     */
    private $createCommandFolderStage;

    /**
     * CreateCommandFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                        $pipeline
     * @param ValidateModuleNamePipeline                                       $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateCommandFolderStage $createCommandFolderStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateCommandFolderStage $createCommandFolderStage
    ) {
        $this->pipeline                   = $pipeline;
        $this->validateModuleNamePipeline = $validateModuleNamePipeline;
        $this->createCommandFolderStage   = $createCommandFolderStage;
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
     * Define the Stages/Pipelines in this Pipeline, then execute it
     *
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function processPipeline(array $payload) : array
    {
        $pipeline = $this->pipeline
            ->pipe($this->validateModuleNamePipeline)
            ->pipe($this->createCommandFolderStage);

        // Pass payload onto next stage/pipeline
        return $pipeline->process($payload);
    }
}
