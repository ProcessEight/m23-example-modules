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
 * Class CreateObserverPhpClassFileStage
 *
 * Creates a Observer/OBSERVER_CLASS_NAME.php file
 *
 */
class CreateObserverPhpClassFileStage extends BaseStage
{
    /**
     * @var string
     */
    public $id = 'createObserverPhpClassFileStage';

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
        // Ask the user for the command-name, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::EVENT_NAME] = [
            'name'                    => ConfigKey::EVENT_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Event name, e.g. sales_model_service_quote_submit_success',
            'question'                => '<question>Event name, e.g. sales_model_service_quote_submit_success []: </question> ',
            'question_default_answer' => '',
        ];
        // Ask the user for the command-description, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::OBSERVER_CLASS_NAME] = [
            'name'                    => ConfigKey::OBSERVER_CLASS_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Observer class name, e.g. CustomObserver',
            'question'                => '<question>Observer class name, e.g. CustomObserver [Observer]: </question> ',
            'question_default_answer' => 'Observer',
        ];
        // Ask the user for the command-class-name, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::OBSERVER_DIRECTORY_PATH] = [
            'name'                    => ConfigKey::OBSERVER_DIRECTORY_PATH,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Observer directory path, e.g. VENDOR_NAME/MODULE_NAME/Observer/Path/To/Directory',
            'question'                => '<question>Observer directory path, e.g. VENDOR_NAME/MODULE_NAME/Observer/Path/To/Directory []: </question> ',
            'question_default_answer' => '',
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
        $subfolderPath     = 'Observer';
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName  = ucfirst(
            str_replace(
                '{{OBSERVER_CLASS_NAME}}',
                $payload['config'][$this->id]['values'][ConfigKey::OBSERVER_CLASS_NAME],
                '{{OBSERVER_CLASS_NAME}}.php'
            )
        );
        $templateFilePath  = $this->template->getTemplateFilePath(
            '{{OBSERVER_CLASS_NAME}}.php.template',
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
            '{{VENDOR_NAME}}'                    => $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}'                    => $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{VENDOR_NAME_LOWERCASE}}'          => strtolower($payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]),
            '{{MODULE_NAME_LOWERCASE}}'          => strtolower($payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME]),
            '{{YEAR}}'                           => date('Y'),
            '{{EVENT_NAME}}'                     => ucfirst($payload['config'][$stageId]['values'][ConfigKey::EVENT_NAME]),
            '{{OBSERVER_DIRECTORY_PATH}}'        => $payload['config'][$stageId]['values'][ConfigKey::OBSERVER_DIRECTORY_PATH],
            '{{OBSERVER_CLASS_NAME}}'            => $payload['config'][$stageId]['values'][ConfigKey::OBSERVER_CLASS_NAME],
            '{{OBSERVER_CLASS_NAME_STRTOLOWER}}' => strtolower($payload['config'][$stageId]['values'][ConfigKey::OBSERVER_CLASS_NAME]),
        ];
    }
}
