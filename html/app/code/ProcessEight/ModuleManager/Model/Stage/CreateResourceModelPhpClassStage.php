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
 * Class CreateResourceModelPhpClassStage
 *
 * Creates a Model/ResourceModel/{{ENTITY_NAME}}.php file
 *
 */
class CreateResourceModelPhpClassStage extends BaseStage
{
    /**
     * @var string
     */
    public $id = 'createResourceModelPhpClassStage';

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
        // Ask the user for the TABLE_NAME, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::TABLE_NAME] = [
            'name'                    => ConfigKey::TABLE_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Database table name, e.g. {{VENDOR_NAME}}_{{MODULE_NAME}}_boondoggle',
            'question'                => '<question>Database table name, e.g. {{VENDOR_NAME}}_{{MODULE_NAME}}_boondoggle [boondoggle]: </question> ',
            'question_default_answer' => 'boondoggle',
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
        $subfolderPath     = 'Model' . DIRECTORY_SEPARATOR . 'ResourceModel';
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName  = ucfirst(
            str_replace(
                '{{ENTITY_NAME}}',
                $payload['config'][$this->id]['values'][ConfigKey::ENTITY_NAME],
                '{{ENTITY_NAME}}.php'
            )
        );
        $templateFilePath  = $this->template->getTemplateFilePath(
            '{{ENTITY_NAME}}.php.template',
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
            '{{ENTITY_NAME}}'           => ucfirst($payload['config'][$stageId]['values'][ConfigKey::ENTITY_NAME]),
            '{{TABLE_NAME}}'            => strtolower($payload['config'][$stageId]['values'][ConfigKey::TABLE_NAME]),
            '{{TABLE_PRIMARY_KEY}}'     => strtolower($payload['config'][$stageId]['values'][ConfigKey::TABLE_PRIMARY_KEY]),
        ];
    }
}