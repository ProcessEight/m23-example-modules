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
 * Class CreateViewModelPhpClassStage
 *
 * Creates a PHP class file.
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateViewModelPhpClassStage extends \ProcessEight\ModuleManager\Model\Stage\BaseStage
{
    /**
     * @var string
     */
    public $id = 'createViewModelPhpClassFileStage';

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
     * Constructor
     *
     * @param \Magento\Framework\Filesystem\Driver\File    $filesystemDriver
     * @param \ProcessEight\ModuleManager\Service\Folder   $folder
     * @param \ProcessEight\ModuleManager\Service\Template $template
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \ProcessEight\ModuleManager\Service\Folder $folder,
        \ProcessEight\ModuleManager\Service\Template $template
    ) {
        $this->filesystemDriver = $filesystemDriver;
        $this->folder           = $folder;
        $this->template         = $template;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function configureStage(array $payload) : array
    {
        // Ask the user for the VIEW_MODEL_CLASS_NAME, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::VIEW_MODEL_CLASS_NAME] = [
            'name'                    => ConfigKey::VIEW_MODEL_CLASS_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'View Model class name, e.g. CustomClassName',
            'question'                => '<question>View Model class name, e.g. CustomClassName [Custom]:</question> ',
            'question_default_answer' => 'Custom',
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
        $subfolder         = 'ViewModel'; // . DIRECTORY_SEPARATOR . ucfirst($payload['config'][$this->id]['values'][ConfigKey::VIEW_MODEL_SUBDIRECTORY_PATH]);
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder(
            $payload,
            $this->id,
            $subfolder
        );
        $artefactFileName  = ucfirst(
            str_replace(
                '{{VIEW_MODEL_CLASS_NAME}}',
                $payload['config'][$this->id]['values'][ConfigKey::VIEW_MODEL_CLASS_NAME],
                '{{VIEW_MODEL_CLASS_NAME}}.php'
            )
        );
        $templateFilePath  = $this->template->getTemplateFilePath(
            '{{VIEW_MODEL_CLASS_NAME}}.php.template',
            'ViewModel'
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
        try {
            // Read template
            $artefactFileTemplate = $this->template->getProcessedTemplate($templateFilePath, $templateVariables);

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen(
                $artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName,
                'wb+'
            );
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }
        $payload['messages'][] = "Created <info>" . $artefactFileName . "</info> file at <info>{$artefactFilePath}</info>";

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
            '{{VENDOR_NAME}}'                   => $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}'                   => $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{VENDOR_NAME_LOWERCASE}}'         => strtolower($payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]),
            '{{MODULE_NAME_LOWERCASE}}'         => strtolower($payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME]),
            '{{YEAR}}'                          => date('Y'),
            /**
             * @todo These kind of Command-specific template variables should be moved out of here
             *       This stage is for creating a php file
             *       Updating the php file to include command-specific template variables should be added to a new 'UpdateWhateverPhpFileStage'
             */
            '{{VIEW_MODEL_CLASS_NAME}}'         => $payload['config'][$stageId]['values'][ConfigKey::VIEW_MODEL_CLASS_NAME],
            '{{VIEW_MODEL_CLASS_NAME_UCFIRST}}' => ucfirst($payload['config'][$stageId]['values'][ConfigKey::VIEW_MODEL_CLASS_NAME]),
//            '{{VIEW_MODEL_SUBDIRECTORY_PATH}}'  => $payload['config'][$stageId]['values'][ConfigKey::VIEW_MODEL_SUBDIRECTORY_PATH],
        ];
    }
}
