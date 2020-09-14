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
 * Class CreateAdminhtmlPluginPipeline
 *
 * This pipeline:
 * - Calls the CreateFolderPipeline
 * - Creates the PHP Plugin class
 * - Creates the di.xml file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateAdminhtmlPluginPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;
    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreatePluginFolderStage
     */
    private $createPluginFolderStage;
    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreatePluginPhpClassFileStage
     */
    private $createPluginPhpClassFileStage;
    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlDiXmlFileStage
     */
    private $createAdminhtmlDiXmlFileStage;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                       $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline           $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreatePluginFolderStage                 $createPluginFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreatePluginPhpClassFileStage           $createPluginPhpClassFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlDiXmlFileStage $createAdminhtmlDiXmlFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreatePluginFolderStage $createPluginFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreatePluginPhpClassFileStage $createPluginPhpClassFileStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlDiXmlFileStage $createAdminhtmlDiXmlFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline    = $validateModuleNamePipeline;
        $this->createPluginFolderStage       = $createPluginFolderStage;
        $this->createPluginPhpClassFileStage = $createPluginPhpClassFileStage;
        $this->createAdminhtmlDiXmlFileStage = $createAdminhtmlDiXmlFileStage;
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
            // Create the Plugin/ folder
            ->pipe($this->validateModuleNamePipeline)
            // Create the Plugin/PluginClassName.php class
            ->pipe($this->createPluginFolderStage)
            // Create the etc/adminhtml/ folder
            ->pipe($this->createPluginPhpClassFileStage)
            // Create the etc/adminhtml/di.xml
            ->pipe($this->createAdminhtmlDiXmlFileStage);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
