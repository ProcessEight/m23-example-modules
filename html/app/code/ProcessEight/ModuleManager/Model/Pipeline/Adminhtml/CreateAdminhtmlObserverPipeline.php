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

namespace ProcessEight\ModuleManager\Model\Pipeline\Adminhtml;

use ProcessEight\ModuleManager\Model\Pipeline\BasePipeline;

/**
 * Class CreateAdminhtmlObserverPipeline
 *
 */
class CreateAdminhtmlObserverPipeline extends BasePipeline
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
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateEtcAdminhtmlFolderStage
     */
    private $createEtcAdminhtmlFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlEventsXmlFileStage
     */
    private $createAdminhtmlEventsXmlFileStage;

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
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateEtcAdminhtmlFolderStage     $createEtcAdminhtmlFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlEventsXmlFileStage $createAdminhtmlEventsXmlFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateObserverPhpClassFileStage           $createObserverPhpClassFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateObserverFolderStage $createObserverFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateEtcAdminhtmlFolderStage $createEtcAdminhtmlFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlEventsXmlFileStage $createAdminhtmlEventsXmlFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateObserverPhpClassFileStage $createObserverPhpClassFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline       = $validateModuleNamePipeline;
        $this->createObserverFolderStage        = $createObserverFolderStage;
        $this->createEtcAdminhtmlFolderStage     = $createEtcAdminhtmlFolderStage;
        $this->createAdminhtmlEventsXmlFileStage = $createAdminhtmlEventsXmlFileStage;
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
            // Create the adminhtml controller folder
            ->pipe($this->createObserverFolderStage)
            // Create the etc/adminhtml folder
            ->pipe($this->createEtcAdminhtmlFolderStage)
            // Create the adminhtml routes.xml
            ->pipe($this->createAdminhtmlEventsXmlFileStage)
            // Create the adminhtml controller PHP class
            ->pipe($this->createObserverPhpClassFileStage);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
