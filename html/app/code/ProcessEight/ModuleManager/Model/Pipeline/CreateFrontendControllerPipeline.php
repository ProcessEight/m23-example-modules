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
 * Class CreateFrontendControllerPipeline
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
class CreateFrontendControllerPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendControllerFolderStage
     */
    private $createFrontendControllerFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateEtcFrontendFolderStage
     */
    private $createEtcFrontendFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendRoutesXmlFileStage
     */
    private $createFrontendRoutesXmlFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendControllerPhpClassFileStage
     */
    private $createFrontendControllerPhpClassFileStage;

    /**
     * Constructor
     *
     * @param \League\Pipeline\Pipeline                                                                  $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline                      $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendControllerFolderStage       $createFrontendControllerFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateEtcFrontendFolderStage              $createEtcFrontendFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendRoutesXmlFileStage                   $createFrontendRoutesXmlFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendControllerPhpClassFileStage $createFrontendControllerPhpClassFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendControllerFolderStage $createFrontendControllerFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateEtcFrontendFolderStage $createEtcFrontendFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendRoutesXmlFileStage $createFrontendRoutesXmlFileStage,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendControllerPhpClassFileStage $createFrontendControllerPhpClassFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline                = $validateModuleNamePipeline;
        $this->createFrontendControllerFolderStage       = $createFrontendControllerFolderStage;
        $this->createEtcFrontendFolderStage              = $createEtcFrontendFolderStage;
        $this->createFrontendRoutesXmlFileStage          = $createFrontendRoutesXmlFileStage;
        $this->createFrontendControllerPhpClassFileStage = $createFrontendControllerPhpClassFileStage;
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
            // Create the frontend controller folder
            ->pipe($this->createFrontendControllerFolderStage)
            // Create the etc/frontend folder
            ->pipe($this->createEtcFrontendFolderStage)
            // Create the frontend routes.xml
            ->pipe($this->createFrontendRoutesXmlFileStage)
            // Create the frontend controller PHP class
            ->pipe($this->createFrontendControllerPhpClassFileStage)
        ;

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
