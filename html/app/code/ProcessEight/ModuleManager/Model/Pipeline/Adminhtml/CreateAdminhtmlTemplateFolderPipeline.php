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
 * Class CreateAdminhtmlTemplateFolderPipeline
 *
 * This pipeline:
 * - Calls the ValidateModuleNamePipeline
 * - Creates the folder
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateAdminhtmlTemplateFolderPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlTemplateFolderStage
     */
    private $createAdminhtmlTemplateFolderStage;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                                            $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline                $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlTemplateFolderStage $createAdminhtmlTemplateFolderStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\Adminhtml\CreateAdminhtmlTemplateFolderStage $createAdminhtmlTemplateFolderStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline         = $validateModuleNamePipeline;
        $this->createAdminhtmlTemplateFolderStage = $createAdminhtmlTemplateFolderStage;
    }

    /**
     * Define the Stages/Pipelines in this Pipeline, then execute it
     *
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function processPipeline(array $payload) : array
    {
        $pipeline = $this->pipeline
            ->pipe($this->validateModuleNamePipeline)
            ->pipe($this->createAdminhtmlTemplateFolderStage);

        // Pass payload onto next stage/pipeline
        return $pipeline->process($payload);
    }
}
