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
 * Class CreateControllerPipeline
 *
 * This pipeline:
 * - Calls the ValidateModuleNamePipeline
 * - Creates the /controller/example folder
 * - Creates the /controller/example/controller.php file
 * - Creates the /etc/frontend/ folder
 * - Creates the /etc/frontend/routes.xml file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateControllerPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateModuleFolderStage
     */
    private $createModuleFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateEtcFolderStage
     */
    private $createEtcFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage
     */
    private $createXmlFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage
     */
    private $createPhpClassStage;

    /**
     * Constructor
     *
     * @param \League\Pipeline\Pipeline                                             $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateModuleFolderStage       $createModuleFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateEtcFolderStage          $createEtcFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage            $createXmlFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage       $createPhpClassFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateModuleFolderStage $createModuleFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateEtcFolderStage $createEtcFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage $createXmlFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage $createPhpClassFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline = $validateModuleNamePipeline;
        $this->createModuleFolderStage    = $createModuleFolderStage;
        $this->createEtcFolderStage       = $createEtcFolderStage;
        $this->createXmlFileStage         = $createXmlFileStage;
        $this->createPhpClassStage        = $createPhpClassFileStage;
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
        // Add the Pipelines/Stages we need for this command
        $pipeline = $this->pipeline
            // Validate the module name
            ->pipe($this->validateModuleNamePipeline)
            // Create the controller folder
            ->pipe($this->createModuleFolderStage)
            // Create the etc folder
            ->pipe($this->createEtcFolderStage)
            // Create the routes.xml
            ->pipe($this->createXmlFileStage)
            // Create the PHP class
            ->pipe($this->createPhpClassStage);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
