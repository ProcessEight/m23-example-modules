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
 * Class CreateAdminhtmlBlockCommandPipeline
 *
 * This pipeline:
 * - Calls the CreateFolderPipeline
 * - Creates the PHP Block class
 *
 */
class CreateAdminhtmlBlockCommandPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlBlockFolderStage
     */
    private $createAdminhtmlBlockFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlBlockPhpClassFileStage
     */
    private $createAdminhtmlBlockPhpClassFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlLayoutFolderStage
     */
    private $createAdminhtmlLayoutFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlLayoutXmlFileStage
     */
    private $createAdminhtmlLayoutXmlFileStage;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                             $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline                 $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlBlockFolderStage       $createAdminhtmlBlockFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlBlockPhpClassFileStage $createAdminhtmlBlockPhpClassFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlLayoutFolderStage      $createAdminhtmlLayoutFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlLayoutXmlFileStage     $createAdminhtmlLayoutXmlFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlBlockFolderStage $createAdminhtmlBlockFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlBlockPhpClassFileStage $createAdminhtmlBlockPhpClassFileStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlLayoutFolderStage $createAdminhtmlLayoutFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlLayoutXmlFileStage $createAdminhtmlLayoutXmlFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline           = $validateModuleNamePipeline;
        $this->createAdminhtmlBlockFolderStage       = $createAdminhtmlBlockFolderStage;
        $this->createAdminhtmlBlockPhpClassFileStage = $createAdminhtmlBlockPhpClassFileStage;
        $this->createAdminhtmlLayoutFolderStage      = $createAdminhtmlLayoutFolderStage;
        $this->createAdminhtmlLayoutXmlFileStage     = $createAdminhtmlLayoutXmlFileStage;
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
            // Create the folder
            ->pipe($this->createAdminhtmlBlockFolderStage)
            // Create the class
            ->pipe($this->createAdminhtmlBlockPhpClassFileStage)
            // Create the layout XML folder
            ->pipe($this->createAdminhtmlLayoutFolderStage)
            // Create the layout XML file
            ->pipe($this->createAdminhtmlLayoutXmlFileStage)
            /**
             * @todo Update the layout XML file to include the block XML declaration
             */
        ;

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
