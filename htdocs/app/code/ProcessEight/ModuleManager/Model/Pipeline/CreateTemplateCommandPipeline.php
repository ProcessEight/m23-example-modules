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

namespace ProcessEight\ModuleManager\Model\Pipeline;

/**
 * Class CreateTemplateCommandPipeline
 *
 * This pipeline:
 * - Calls the CreateFolderPipeline
 * - Creates the phtml template file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateTemplateCommandPipeline
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * @var CreateFolderPipeline
     */
    private $createFolderPipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateTemplatePhtmlFile
     */
    private $createTemplatePhtmlFile;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                       $pipeline
     * @param CreateFolderPipeline                                            $createFolderPipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateTemplatePhtmlFile $createTemplatePhtmlFile
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateFolderPipeline $createFolderPipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateTemplatePhtmlFile $createTemplatePhtmlFile
    ) {
        $this->pipeline                = $pipeline;
        $this->createFolderPipeline    = $createFolderPipeline;
        $this->createTemplatePhtmlFile = $createTemplatePhtmlFile;
    }

    /**
     * Called when this pipeline is invoked by another pipeline/stage (as opposed to being injected by DI)
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
     * Define and configure the stages in this pipeline, then execute it
     *
     * @param mixed[] $payload Values which need to be passed from stage to stage
     *
     * @return mixed[]
     */
    public function processPipeline(array $payload) : array
    {
        // Add the Pipelines/Stages we need for this command
        $pipeline = $this->pipeline
            // Create the folder
            ->pipe($this->createFolderPipeline)
            // Create the phtml template file
            ->pipe($this->createTemplatePhtmlFile)
        ;

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
