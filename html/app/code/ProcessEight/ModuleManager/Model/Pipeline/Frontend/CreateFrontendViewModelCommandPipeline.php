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
 * Class CreateFrontendViewModelCommandPipeline
 *
 */
class CreateFrontendViewModelCommandPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateViewModelFolderStage
     */
    private $createViewModelFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateViewModelPhpClassStage
     */
    private $createViewModelPhpClassFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\AddViewModelToFrontendLayoutXmlStage
     */
    private $addViewModelToFrontendLayoutXmlStage;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                    $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline        $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateViewModelFolderStage           $createViewModelFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateViewModelPhpClassStage         $createViewModelPhpClassFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\AddViewModelToFrontendLayoutXmlStage $addViewModelToFrontendLayoutXmlStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateViewModelFolderStage $createViewModelFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateViewModelPhpClassStage $createViewModelPhpClassFileStage,
        \ProcessEight\ModuleManager\Model\Stage\AddViewModelToFrontendLayoutXmlStage $addViewModelToFrontendLayoutXmlStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline           = $validateModuleNamePipeline;
        $this->createViewModelFolderStage           = $createViewModelFolderStage;
        $this->createViewModelPhpClassFileStage     = $createViewModelPhpClassFileStage;
        $this->addViewModelToFrontendLayoutXmlStage = $addViewModelToFrontendLayoutXmlStage;
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
            // Validate the module name
            ->pipe($this->validateModuleNamePipeline)
            // Create the folder
            ->pipe($this->createViewModelFolderStage)
            // Create the class
            ->pipe($this->createViewModelPhpClassFileStage)
            // Add the View Model XML to the Layout XML file
            ->pipe($this->addViewModelToFrontendLayoutXmlStage);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
