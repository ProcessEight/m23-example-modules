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
 * Class CreateFrontendObserverPipeline
 *
 */
class CreateFrontendObserverPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateObserverFolderStage
     */
    private $createObserverFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateEtcFrontendFolderStage
     */
    private $createEtcFrontendFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendEventsXmlFileStage
     */
    private $createFrontendEventsXmlFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateObserverPhpClassFileStage
     */
    private $createObserverPhpClassFileStage;

    /**
     * Constructor
     *
     * @param \League\Pipeline\Pipeline                                                         $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline             $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateObserverFolderStage                 $createObserverFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateEtcFrontendFolderStage     $createEtcFrontendFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendEventsXmlFileStage $createFrontendEventsXmlFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateObserverPhpClassFileStage           $createObserverPhpClassFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateObserverFolderStage $createObserverFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateEtcFrontendFolderStage $createEtcFrontendFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Frontend\CreateFrontendEventsXmlFileStage $createFrontendEventsXmlFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateObserverPhpClassFileStage $createObserverPhpClassFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline       = $validateModuleNamePipeline;
        $this->createObserverFolderStage        = $createObserverFolderStage;
        $this->createEtcFrontendFolderStage     = $createEtcFrontendFolderStage;
        $this->createFrontendEventsXmlFileStage = $createFrontendEventsXmlFileStage;
        $this->createObserverPhpClassFileStage  = $createObserverPhpClassFileStage;
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
            ->pipe($this->createObserverFolderStage)
            // Create the etc/frontend folder
            ->pipe($this->createEtcFrontendFolderStage)
            // Create the frontend routes.xml
            ->pipe($this->createFrontendEventsXmlFileStage)
            // Create the frontend controller PHP class
            ->pipe($this->createObserverPhpClassFileStage);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
