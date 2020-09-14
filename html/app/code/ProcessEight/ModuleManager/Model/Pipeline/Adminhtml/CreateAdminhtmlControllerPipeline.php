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
 * Class CreateAdminhtmlControllerPipeline
 *
 * This pipeline:
 * - Calls the CreateFolderPipeline
 * - Creates the PHP Command class
 * - Creates the di.xml file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateAdminhtmlControllerPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlControllerFolderStage
     */
    private $createAdminhtmlControllerFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateEtcAdminhtmlFolderStage
     */
    private $createEtcAdminhtmlFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlRoutesXmlFileStage
     */
    private $createAdminhtmlRoutesXmlFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlControllerPhpClassFileStage
     */
    private $createAdminhtmlControllerPhpClassFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlMenuXmlFileStage
     */
    private $createAdminhtmlMenuXmlFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateAclXmlFileStage
     */
    private $createAclXmlFileStage;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                                    $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline                        $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlControllerFolderStage       $createAdminhtmlControllerFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateEtcAdminhtmlFolderStage              $createEtcAdminhtmlFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlRoutesXmlFileStage          $createAdminhtmlRoutesXmlFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlControllerPhpClassFileStage $createAdminhtmlControllerPhpClassFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlMenuXmlFileStage            $createAdminhtmlMenuXmlFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateAclXmlFileStage                                $createAclXmlFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlControllerFolderStage $createAdminhtmlControllerFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateEtcAdminhtmlFolderStage $createEtcAdminhtmlFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlRoutesXmlFileStage $createAdminhtmlRoutesXmlFileStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlControllerPhpClassFileStage $createAdminhtmlControllerPhpClassFileStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlMenuXmlFileStage $createAdminhtmlMenuXmlFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateAclXmlFileStage $createAclXmlFileStage
    ) {
        parent::__construct($pipeline);

        $this->validateModuleNamePipeline                 = $validateModuleNamePipeline;
        $this->createAdminhtmlControllerFolderStage       = $createAdminhtmlControllerFolderStage;
        $this->createEtcAdminhtmlFolderStage              = $createEtcAdminhtmlFolderStage;
        $this->createAdminhtmlRoutesXmlFileStage          = $createAdminhtmlRoutesXmlFileStage;
        $this->createAdminhtmlControllerPhpClassFileStage = $createAdminhtmlControllerPhpClassFileStage;
        $this->createAdminhtmlMenuXmlFileStage            = $createAdminhtmlMenuXmlFileStage;
        $this->createAclXmlFileStage                      = $createAclXmlFileStage;
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
            // Create the etc/adminhtml folder
            ->pipe($this->createEtcAdminhtmlFolderStage)
            // Create the adminhtml menu.xml
            ->pipe($this->createAdminhtmlMenuXmlFileStage)
            // Create the adminhtml routes.xml
            ->pipe($this->createAdminhtmlRoutesXmlFileStage)
            // Create the adminhtml controller folder
            ->pipe($this->createAdminhtmlControllerFolderStage)
            // Create the adminhtml controller PHP class
            ->pipe($this->createAdminhtmlControllerPhpClassFileStage)
            // Create the acl.xml
            ->pipe($this->createAclXmlFileStage);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
