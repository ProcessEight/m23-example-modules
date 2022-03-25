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

namespace ProcessEight\ModuleManager\Model\Pipeline;

/**
 * Class CreateRepositoryTriadPipeline
 *
 */
class CreateRepositoryTriadPipeline extends BasePipeline
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline
     */
    private $validateModuleNamePipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateModelFolderStage
     */
    private $createModelFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateModelPhpClassStage
     */
    private $createModelPhpClassStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateApiFolderStage
     */
    private $createApiFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateApiDataFolderStage
     */
    private $createApiDataFolderStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateModelPhpInterfaceStage
     */
    private $createModelPhpInterfaceStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateSearchResultsPhpInterfaceStage
     */
    private $createSearchResultsPhpInterfaceStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateRepositoryPhpInterfaceStage
     */
    private $createRepositoryPhpInterfaceStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateDiXmlFileStage
     */
    private $createDiXmlFileStage;

    /**
     * Constructor
     *
     * @param \League\Pipeline\Pipeline                                                    $pipeline
     * @param \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline        $validateModuleNamePipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateModelFolderStage               $createModelFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateModelPhpClassStage             $createModelPhpClassStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateApiFolderStage                 $createApiFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateApiDataFolderStage             $createApiDataFolderStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateModelPhpInterfaceStage         $createModelPhpInterfaceStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateSearchResultsPhpInterfaceStage $createSearchResultsPhpInterfaceStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateRepositoryPhpInterfaceStage    $createRepositoryPhpInterfaceStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateDiXmlFileStage                 $createDiXmlFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Pipeline\ValidateModuleNamePipeline $validateModuleNamePipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreateModelFolderStage $createModelFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateModelPhpClassStage $createModelPhpClassStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateApiFolderStage $createApiFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateApiDataFolderStage $createApiDataFolderStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateModelPhpInterfaceStage $createModelPhpInterfaceStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateSearchResultsPhpInterfaceStage $createSearchResultsPhpInterfaceStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateRepositoryPhpInterfaceStage $createRepositoryPhpInterfaceStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateDiXmlFileStage $createDiXmlFileStage
    ) {
        parent::__construct($pipeline);
        $this->validateModuleNamePipeline           = $validateModuleNamePipeline;
        $this->createModelFolderStage               = $createModelFolderStage;
        $this->createModelPhpClassStage             = $createModelPhpClassStage;
        $this->createApiFolderStage                 = $createApiFolderStage;
        $this->createApiDataFolderStage             = $createApiDataFolderStage;
        $this->createModelPhpInterfaceStage         = $createModelPhpInterfaceStage;
        $this->createSearchResultsPhpInterfaceStage = $createSearchResultsPhpInterfaceStage;
        $this->createRepositoryPhpInterfaceStage    = $createRepositoryPhpInterfaceStage;
        $this->createDiXmlFileStage                 = $createDiXmlFileStage;
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
            ->pipe($this->createModelFolderStage)
            ->pipe($this->createModelPhpClassStage)
            ->pipe($this->createApiFolderStage)
            ->pipe($this->createApiDataFolderStage)
            ->pipe($this->createModelPhpInterfaceStage)
            ->pipe($this->createSearchResultsPhpInterfaceStage)
            ->pipe($this->createRepositoryPhpInterfaceStage)
//            ->pipe($this->createDiXmlFileStage)
        ;

        // Pass payload onto next Stage/Pipeline
        return $pipeline->process($payload);
    }
}
