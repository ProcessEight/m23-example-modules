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
 * Class CreateBinMagentoCommandPipeline
 *
 * This pipeline:
 * - Calls the CreateFolderPipeline
 * - Creates the PHP Command class
 * - Creates the di.xml file
 *
 * @package ProcessEight\ModuleManager\Model\Pipeline
 */
class CreateBinMagentoCommandPipeline
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
     * @var CreateFolderPipeline
     */
    private $createFolderPipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage
     */
    private $createPhpClassFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage
     */
    private $createXmlFileStage;

    /**
     * CreateFolderPipeline constructor.
     *
     * @param \League\Pipeline\Pipeline                                       $pipeline
     * @param CreateFolderPipeline                                            $createFolderPipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage $createPhpClassFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage      $createXmlFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateFolderPipeline $createFolderPipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage $createPhpClassFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage $createXmlFileStage
    ) {
        $this->pipeline                = $pipeline;
        $this->createFolderPipeline    = $createFolderPipeline;
        $this->createPhpClassFileStage = $createPhpClassFileStage;
        $this->createXmlFileStage      = $createXmlFileStage;
    }

    /**
     * Called when this pipeline is invoked by another pipeline/stage (as opposed to being inject by DI)
     *
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function __invoke(array $payload) : array
    {
        if ($payload['is_valid'] === true) {
            $payload = $this->processPipeline($payload);
        }

        return $payload;
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
        $config = $this->getConfig();

        $this->createFolderPipeline->setConfig($config);

        // 'Create PHP Class' Stage config
        $this->createPhpClassFileStage->setFileName($config['createPhpClassFileStage']['file-name']);
        $this->createPhpClassFileStage->setFilePath($config['createPhpClassFileStage']['file-path']);
        $this->createPhpClassFileStage->setTemplateFilePath($config['createPhpClassFileStage']['template-file-path']);
        $this->createPhpClassFileStage->setTemplateVariables($config['createPhpClassFileStage']['template-variables']);

        // 'Create XML File' Stage config
        $this->createXmlFileStage->setFileName($config['createXmlFileStage']['file-name']);
        $this->createXmlFileStage->setFilePath($config['createXmlFileStage']['file-path']);
        $this->createXmlFileStage->setTemplateFilePath($config['createXmlFileStage']['template-file-path']);
        $this->createXmlFileStage->setTemplateVariables($config['createXmlFileStage']['template-variables']);

        // Add the Pipelines/Stages we need for this command
        $pipeline = $this->pipeline
            // Create the folder
            ->pipe($this->createFolderPipeline)
            // Create the class
            ->pipe($this->createPhpClassFileStage)
            // Create the di.xml
            ->pipe($this->createXmlFileStage)
        ;

        // Pass payload onto next Stage/Pipeline
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
