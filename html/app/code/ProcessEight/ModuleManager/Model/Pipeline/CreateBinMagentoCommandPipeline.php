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

/**
 * Class CreateBinMagentoCommandPipeline
 *
 * This pipeline:
 * - Calls the CreateCommandFolderPipeline
 * - Creates the PHP Command class
 * - Creates the di.xml file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateBinMagentoCommandPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateCommandFolderPipeline
     */
    private $createCommandFolderPipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateBinMagentoCommandClassFileStage
     */
    private $createBinMagentoCommandClassFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateDiXmlFileStage
     */
    private $createDiXmlFileStage;

    /**
     * CreateCommandFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                     $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateCommandFolderPipeline        $createCommandFolderPipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateBinMagentoCommandClassFileStage $createBinMagentoCommandClassFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateDiXmlFileStage                  $createDiXmlFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateCommandFolderPipeline $createCommandFolderPipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateBinMagentoCommandClassFileStage $createBinMagentoCommandClassFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateDiXmlFileStage $createDiXmlFileStage
    ) {
        parent::__construct($pipeline);
        $this->createCommandFolderPipeline           = $createCommandFolderPipeline;
        $this->createBinMagentoCommandClassFileStage = $createBinMagentoCommandClassFileStage;
        $this->createDiXmlFileStage                  = $createDiXmlFileStage;
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
            ->pipe($this->createCommandFolderPipeline)
            // Create the class
            ->pipe($this->createBinMagentoCommandClassFileStage)
            // Create the di.xml
            ->pipe($this->createDiXmlFileStage)
            // Update the di.xml with our command config (coming soon)
//            ->pipe($this->updateDiXmlFileStage)
        ;

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
