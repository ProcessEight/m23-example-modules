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
 * Class CreateInstallSchemaPipeline
 *
 */
class CreateInstallSchemaPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateSetupFolderStage
     */
    private $createSetupFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\AddSetupVersionToModuleXmlStage
     */
    private $addSetupVersionToModuleXmlStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateInstallSchemaPhpClassStage
     */
    private $createInstallSchemaPhpClassStage;

    /**
     * Constructor
     *
     * @param \League\Pipeline\Pipeline                                               $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline   $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateSetupFolderStage          $createSetupFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\AddSetupVersionToModuleXmlStage $addSetupVersionToModuleXmlStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateInstallSchemaPhpClassStage  $createInstallSchemaPhpClassStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateSetupFolderStage $createSetupFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\AddSetupVersionToModuleXmlStage $addSetupVersionToModuleXmlStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateInstallSchemaPhpClassStage $createInstallSchemaPhpClassStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline      = $validateModuleNamePipeline;
        $this->createSetupFolderStage          = $createSetupFolderStage;
        $this->addSetupVersionToModuleXmlStage = $addSetupVersionToModuleXmlStage;
        $this->createInstallSchemaPhpClassStage  = $createInstallSchemaPhpClassStage;
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
            ->pipe($this->createSetupFolderStage)
            // Create the frontend routes.xml
            ->pipe($this->addSetupVersionToModuleXmlStage)
            // Create the frontend controller PHP class
            ->pipe($this->createInstallSchemaPhpClassStage);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
