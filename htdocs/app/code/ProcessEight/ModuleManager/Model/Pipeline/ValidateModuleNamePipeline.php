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
 * Class ValidateModuleNamePipeline
 *
 * This pipeline:
 * - Validates the vendor name
 * - Validates the module name
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class ValidateModuleNamePipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\ValidateVendorNameStage
     */
    private $validateVendorNameStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\ValidateModuleNameStage
     */
    private $validateModuleNameStage;

    /**
     * ValidateModuleNamePipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                       $pipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateVendorNameStage $validateVendorNameStage
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateModuleNameStage $validateModuleNameStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Stage\ValidateVendorNameStage $validateVendorNameStage,
        \ProcessEight\ModuleManager\Model\Stage\ValidateModuleNameStage $validateModuleNameStage
    ) {
        parent::__construct($pipeline);
        $this->validateVendorNameStage = $validateVendorNameStage;
        $this->validateModuleNameStage = $validateModuleNameStage;
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
        $pipeline = $this->pipeline
            ->pipe($this->validateVendorNameStage)
            ->pipe($this->validateModuleNameStage);

        // Pass payload onto next stage/pipeline
        return $pipeline->process($payload);
    }
}
