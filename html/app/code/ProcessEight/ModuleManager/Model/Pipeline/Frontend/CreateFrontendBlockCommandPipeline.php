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
 * Class CreateFrontendBlockCommandPipeline
 *
 * This pipeline:
 * - Calls the CreateFolderPipeline
 * - Creates the PHP Block class
 *
 */
class CreateFrontendBlockCommandPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendBlockFolderStage
     */
    private $createFrontendBlockFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendBlockPhpClassFileStage
     */
    private $createFrontendBlockPhpClassFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendLayoutFolderStage
     */
    private $createFrontendLayoutFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendLayoutXmlFileStage
     */
    private $createFrontendLayoutXmlFileStage;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                             $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline                 $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendBlockFolderStage       $createFrontendBlockFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendBlockPhpClassFileStage $createFrontendBlockPhpClassFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendLayoutFolderStage      $createFrontendLayoutFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendLayoutXmlFileStage     $createFrontendLayoutXmlFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendBlockFolderStage $createFrontendBlockFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendBlockPhpClassFileStage $createFrontendBlockPhpClassFileStage,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendLayoutFolderStage $createFrontendLayoutFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendLayoutXmlFileStage $createFrontendLayoutXmlFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline           = $validateModuleNamePipeline;
        $this->createFrontendBlockFolderStage       = $createFrontendBlockFolderStage;
        $this->createFrontendBlockPhpClassFileStage = $createFrontendBlockPhpClassFileStage;
        $this->createFrontendLayoutFolderStage      = $createFrontendLayoutFolderStage;
        $this->createFrontendLayoutXmlFileStage     = $createFrontendLayoutXmlFileStage;
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
            ->pipe($this->createFrontendBlockFolderStage)
            // Create the class
            ->pipe($this->createFrontendBlockPhpClassFileStage)
            // Create the layout XML folder
            ->pipe($this->createFrontendLayoutFolderStage)
            // Create the layout XML file
            ->pipe($this->createFrontendLayoutXmlFileStage)
            /**
             * @todo Update the layout XML file to include the block XML declaration
             */
        ;

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
