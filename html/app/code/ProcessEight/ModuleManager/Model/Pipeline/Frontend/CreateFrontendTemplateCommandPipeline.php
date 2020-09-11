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

namespace ProcessEight\ModuleManager\Model\Pipeline\Frontend;

use ProcessEight\ModuleManager\Model\Pipeline\BasePipeline;

/**
 * Class CreateFrontendTemplateCommandPipeline
 *
 * This pipeline:
 * - Calls the createFrontendTemplateFolderPipeline
 * - Creates the phtml template file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateFrontendTemplateCommandPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\Frontend\CreateFrontendTemplateFolderPipeline
     */
    private $createFrontendTemplateFolderPipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateFrontendTemplatePhtmlFileStage
     */
    private $createTemplatePhtmlFile;

    /**
     * CreateFrontendTemplateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                                $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\Frontend\CreateFrontendTemplateFolderPipeline $createFrontendTemplateFolderPipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendTemplatePhtmlFileStage    $createTemplatePhtmlFile
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\Frontend\CreateFrontendTemplateFolderPipeline $createFrontendTemplateFolderPipeline,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendTemplatePhtmlFileStage $createTemplatePhtmlFile
    ) {
        parent::__construct($pipeline);
        $this->createFrontendTemplateFolderPipeline = $createFrontendTemplateFolderPipeline;
        $this->createTemplatePhtmlFile              = $createTemplatePhtmlFile;
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
            ->pipe($this->createFrontendTemplateFolderPipeline)
            // Create the phtml template file
            ->pipe($this->createTemplatePhtmlFile);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
