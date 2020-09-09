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
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage
     */
    private $createDiXmlFileStage;

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
//        $this->createDiXmlFileStage           = clone $createXmlFileStage; // Just an example, you understand
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
        /**
         * Hotfix:
         * How to use one stage to generate different files/folders, each requiring different values?
         * E.g. Use one class to generate two different XML files?
         * The answer is to inject the class once, then clone it (see constructor above)
         * Then we assign different values to the 'id' property
         * This means we can avoid having to create a stage for every conceivable unique folder/file in Magento 2
         */
        $this->createXmlFileStage->id   = 'createModuleXmlFileStage';
//        $this->createDiXmlFileStage->id = 'createDiXmlFileStage';

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
            // Create another XML file (just for testing module-manager-v3)
//            ->pipe($this->createDiXmlFileStage) // Refactor to use module-manager-v3 method of doing things
            // Create the composer.json
            ->pipe($this->createComposerJsonFileStage) // Refactor to use module-manager-v3 method of doing things
            // Create the registration.php
            ->pipe($this->createRegistrationPhpFileStage) // Refactor to use module-manager-v3 method of doing things
        ;

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
