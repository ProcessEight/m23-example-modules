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

namespace ProcessEight\ModuleManager\Model\Stage;

use Magento\Framework\Exception\FileSystemException;
use ProcessEight\ModuleManager\Model\ConfigKey;

/**
 * Class CreateSearchResultsPhpInterfaceStage
 *
 * Creates an Api/Data/{{ENTITY_NAME}}SearchResultsInterface.php file
 *
 */
class CreateSearchResultsPhpInterfaceStage extends BaseStage
{
    /**
     * @var string
     */
    public $id = 'createSearchResultsPhpInterfaceStage';

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var \ProcessEight\ModuleManager\Service\Folder
     */
    private $folder;

    /**
     * @var \ProcessEight\ModuleManager\Service\Template
     */
    private $template;
    /**
     * @var \ProcessEight\ModuleManager\Service\File
     */
    private $fileService;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Filesystem\Driver\File    $filesystemDriver
     * @param \ProcessEight\ModuleManager\Service\Folder   $folder
     * @param \ProcessEight\ModuleManager\Service\Template $template
     * @param \ProcessEight\ModuleManager\Service\File     $fileService
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \ProcessEight\ModuleManager\Service\Folder $folder,
        \ProcessEight\ModuleManager\Service\Template $template,
        \ProcessEight\ModuleManager\Service\File $fileService
    ) {
        $this->filesystemDriver = $filesystemDriver;
        $this->folder           = $folder;
        $this->template         = $template;
        $this->fileService      = $fileService;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function configureStage(array $payload) : array
    {
        // Ask the user for the ENTITY_NAME, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::ENTITY_NAME] = [
            'name'                    => ConfigKey::ENTITY_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Model entity name, e.g. Order, Customer, Boondoggle',
            'question'                => '<question>Model entity name, e.g. order, customer, widget [Boondoggle]: </question> ',
            'question_default_answer' => 'Boondoggle',
        ];

        return $payload;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $subfolderPath     = 'Api' . DIRECTORY_SEPARATOR . 'Data';
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName  = str_replace(
            '{{ENTITY_NAME}}',
            ucfirst($payload['config'][$this->id]['values'][ConfigKey::ENTITY_NAME]),
            '{{ENTITY_NAME}}SearchResultsInterface.php'
        );
        $templateFilePath  = $this->template->getTemplateFilePath(
            '{{ENTITY_NAME}}SearchResultsInterface.php.template',
            $subfolderPath
        );
        $templateVariables = $this->getTemplateVariables($this->id, $payload);

        // Check if file exists
        try {
            $isExists = $this->filesystemDriver->isExists($artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);
            if ($isExists) {
                $payload['messages'][] = "<info>" . $artefactFileName . "</info> file already exists at <info>" . $artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName . "</info>";
                $payload['messages'][] = "<info>TODO: Add logic to modify existing files. For now, copy and paste the following into " . $artefactFileName . "</info>";
                $payload['messages'][] = "<info>" .
                                         $this->template->getProcessedTemplate($templateFilePath, $templateVariables) .
                                         "</info>";

                return $payload;
            }
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        // Create file from template
        $this->fileService->createFileFromTemplate(
            $payload,
            $templateFilePath,
            $templateVariables,
            $artefactFilePath,
            $artefactFileName
        );

        // Pass payload onto next stage/pipeline
        return $payload;
    }

    /**
     * All template variables used by this stage
     *
     * @param string $stageId
     * @param array  $payload
     *
     * @return array
     */
    public function getTemplateVariables(string $stageId, array $payload) : array
    {
        return [
            '{{VENDOR_NAME}}' => $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}' => $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{ENTITY_NAME}}' => ucfirst($payload['config'][$stageId]['values'][ConfigKey::ENTITY_NAME]),
        ];
    }
}
