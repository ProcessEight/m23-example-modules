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

namespace ProcessEight\ModuleManager\Model\Stage;

use Magento\Framework\Exception\FileSystemException;
use ProcessEight\ModuleManager\Model\ConfigKey;

/**
 * Class CreateBinMagentoCommandClassFileStage
 *
 * Creates a bin/magento command PHP class file.
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateBinMagentoCommandClassFileStage extends BaseStage
{
    public $id = 'createBinMagentoCommandClassFileStage';

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var \ProcessEight\ModuleManager\Model\Folder
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
     * @param \ProcessEight\ModuleManager\Model\Folder     $folder
     * @param \ProcessEight\ModuleManager\Service\Template $template
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \ProcessEight\ModuleManager\Model\Folder $folder,
        \ProcessEight\ModuleManager\Service\Template $template
    ) {
        $this->filesystemDriver = $filesystemDriver;
        $this->folder           = $folder;
        $this->template         = $template;
    }

    /**
     *
     * @param array $payload
     *
     * @return array
     */
    public function configureStage(array $payload) : array
    {
        // Ask the user for the command-name, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::COMMAND_NAME] = [
            'name'                    => ConfigKey::COMMAND_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Command name, e.g. process-eight:module:create',
            'question'                => '<question>Command name [process-eight:command]: </question> ',
            'question_default_answer' => 'process-eight:command',
        ];
        // Ask the user for the command-description, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::COMMAND_DESCRIPTION] = [
            'name'                    => ConfigKey::COMMAND_DESCRIPTION,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Brief description of the command',
            'question'                => '<question>Command description [This is a bin/magento command]: </question> ',
            'question_default_answer' => 'This is a bin/magento command',
        ];
        // Ask the user for the command-class-name, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::COMMAND_CLASS_NAME] = [
            'name'                    => ConfigKey::COMMAND_CLASS_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Command class name, e.g. CustomCommand',
            'question'                => '<question>Command class name [CustomCommand]: </question> ',
            'question_default_answer' => 'CustomCommand',
        ];

        return $payload;
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $subfolderPath     = 'Command';
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName  = ucfirst(
            str_replace(
                '{{COMMAND_CLASS_NAME}}',
                $payload['config']['createBinMagentoCommandClassFileStage']['values'][ConfigKey::COMMAND_CLASS_NAME],
                '{{COMMAND_CLASS_NAME}}.php'
            )
        );
        $templateFilePath  = $this->template->getTemplateFilePath('{{COMMAND_CLASS_NAME}}.php', $subfolderPath);
        $templateVariables = $this->getTemplateVariables('createBinMagentoCommandClassFileStage', $payload);

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
        $payload['messages'][] = "Created <info>" . $artefactFileName . "</info> file at <info>" . $artefactFilePath . "</info>";

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
            '{{VENDOR_NAME}}'           => $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}'           => $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{VENDOR_NAME_LOWERCASE}}' => strtolower($payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]),
            '{{MODULE_NAME_LOWERCASE}}' => strtolower($payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME]),
            '{{YEAR}}'                  => date('Y'),
            '{{COMMAND_NAME}}'                  => $payload['config'][$stageId]['values'][ConfigKey::COMMAND_NAME],
            '{{COMMAND_DESCRIPTION}}'           => $payload['config'][$stageId]['values'][ConfigKey::COMMAND_DESCRIPTION],
            '{{COMMAND_CLASS_NAME}}'            => $payload['config'][$stageId]['values'][ConfigKey::COMMAND_CLASS_NAME],
            '{{COMMAND_CLASS_NAME_UCFIRST}}'    => ucfirst($payload['config'][$stageId]['values'][ConfigKey::COMMAND_CLASS_NAME]),
            '{{COMMAND_CLASS_NAME_STRTOLOWER}}' => strtolower($payload['config'][$stageId]['values'][ConfigKey::COMMAND_CLASS_NAME]),
        ];
    }
}
