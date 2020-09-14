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
 * Class CreateAdminhtmlTemplateCommandPipeline
 *
 * This pipeline:
 * - Calls the createAdminhtmlTemplateFolderPipeline
 * - Creates the phtml template file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateAdminhtmlTemplateCommandPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlTemplateFolderPipeline
     */
    private $createAdminhtmlTemplateFolderPipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlTemplatePhtmlFileStage
     */
    private $createAdminhtmlTemplatePhtmlFile;

    /**
     * CreateAdminhtmlTemplateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                                  $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlTemplateFolderPipeline $createAdminhtmlTemplateFolderPipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlTemplatePhtmlFileStage    $createAdminhtmlTemplatePhtmlFile
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlTemplateFolderPipeline $createAdminhtmlTemplateFolderPipeline,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlTemplatePhtmlFileStage $createAdminhtmlTemplatePhtmlFile
    ) {
        parent::__construct($pipeline);
        $this->createAdminhtmlTemplateFolderPipeline = $createAdminhtmlTemplateFolderPipeline;
        $this->createAdminhtmlTemplatePhtmlFile      = $createAdminhtmlTemplatePhtmlFile;
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
            // Create the folder
            ->pipe($this->createAdminhtmlTemplateFolderPipeline)
            // Create the PHTML template file
            ->pipe($this->createAdminhtmlTemplatePhtmlFile);

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
