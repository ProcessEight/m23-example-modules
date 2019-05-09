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
 * @category    pipeline-example
 * @copyright   Copyright (c) 2018 ProcessEight
 * @author      ProcessEight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Model\Pipeline;

/**
 * Class CreateFolderPipeline
 *
 * This pipeline:
 * - Validates the vendor name
 * - Validates the module name
 * - Validates the folder name
 * - Creates the folder
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateFolderPipeline
{
    /**
     * @var mixed[]
     */
    private $config;

    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\ValidateVendorNameStage
     */
    private $validateVendorNameStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateFolderStage
     */
    private $createFolderStage;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                       $pipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateVendorNameStage $validateVendorNameStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateFolderStage       $createFolderStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Stage\ValidateVendorNameStage $validateVendorNameStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateFolderStage $createFolderStage
    ) {
        $this->pipeline                = $pipeline;
        $this->validateVendorNameStage = $validateVendorNameStage;
        $this->createFolderStage       = $createFolderStage;
    }

    /**
     * Called when this pipeline is invoked by another pipeline/stage (as opposed to being inject by DI)
     *
     * @inheritdoc
     */
    public function __invoke($payload)
    {
        return $this->processPipeline($payload);
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
        // Each stage should be responsible for the data it needs to work
        // Values which need to be passed from stage to stage should be added to the payload array
        $config = $this->getConfig();
        $this->validateVendorNameStage->setVendorName($config['data']['vendor-name']);
        $this->createFolderStage->setFolderPath($config['data']['path-to-folder']);

        $pipeline = $this->pipeline
            ->pipe($this->validateVendorNameStage)
            ->pipe($this->createFolderStage);

        return $pipeline->process($payload);
    }

    /**
     * @return mixed[]
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * @param mixed[] $config
     */
    public function setConfig(array $config) : void
    {
        $this->config = $config;
    }
}
