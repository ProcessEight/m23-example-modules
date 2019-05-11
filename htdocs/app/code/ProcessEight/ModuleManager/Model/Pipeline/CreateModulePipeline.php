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
 * Class CreateModulePipeline
 *
 * This pipeline:
 * - Calls the ValidateModuleNamePipeline
 * - Creates the module folder
 * - Creates the etc folder
 * - Creates the module.xml file
 * - Creates the composer.json file
 * - Creates the registration.php file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateModulePipeline extends BasePipeline
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
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateComposerJsonFileStage
     */
    private $createComposerJsonFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateRegistrationPhpFileStage
     */
    private $createRegistrationPhpFileStage;

    /**
     * Constructor
     *
     * @param \League\Pipeline\Pipeline                                              $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline  $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateModuleFolderStage        $createModuleFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateEtcFolderStage           $createEtcFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage             $createXmlFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateComposerJsonFileStage    $createComposerJsonFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateRegistrationPhpFileStage $createRegistrationPhpFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateModuleFolderStage $createModuleFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateEtcFolderStage $createEtcFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage $createXmlFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateComposerJsonFileStage $createComposerJsonFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateRegistrationPhpFileStage $createRegistrationPhpFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline     = $validateModuleNamePipeline;
        $this->createModuleFolderStage        = $createModuleFolderStage;
        $this->createEtcFolderStage           = $createEtcFolderStage;
        $this->createXmlFileStage             = $createXmlFileStage;
        $this->createComposerJsonFileStage    = $createComposerJsonFileStage;
        $this->createRegistrationPhpFileStage = $createRegistrationPhpFileStage;
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
            // Create the module folder
            ->pipe($this->createModuleFolderStage)
            // Create the etc folder
            ->pipe($this->createEtcFolderStage)
            // Create the module.xml
            ->pipe($this->createXmlFileStage)
            // Create the composer.json
            ->pipe($this->createComposerJsonFileStage)
            // Create the registration.php
            ->pipe($this->createRegistrationPhpFileStage);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
