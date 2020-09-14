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
 * Class CreatePluginPhpClassFileStage
 *
 * Creates a Plugin/PLUGIN_CLASS_NAME.php file
 *
 */
class CreatePluginPhpClassFileStage extends BaseStage
{
    /**
     * @var string
     */
    public $id = 'createPluginPhpClassFileStage';

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
        // Ask the user for the METHOD_TO_INTERCEPT_NAMESPACE, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE] = [
            'name'                    => ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Method to intercept (in format \Vendor\Namespace\Path\To\Class::methodToIntercept)',
            'question'                => '<question>Method to intercept (in format \Vendor\Namespace\Path\To\Class::methodToIntercept) []: </question> ',
            'question_default_answer' => '',
        ];
        // Ask the user for the PLUGIN_TYPE, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::PLUGIN_TYPE] = [
            'name'                    => ConfigKey::PLUGIN_TYPE,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Plugin type (before/after/around)',
            'question'                => '<question>Plugin type (before/after/around) [before]: </question> ',
            'question_default_answer' => 'before',
        ];
        // Ask the user for the PLUGIN_DIRECTORY_PATH, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::PLUGIN_DIRECTORY_PATH] = [
            'name'                    => ConfigKey::PLUGIN_DIRECTORY_PATH,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Plugin directory path, e.g. VENDOR_NAME/MODULE_NAME/Plugin/Custom/Directory/Path/',
            'question'                => '<question>Plugin directory path, e.g. VENDOR_NAME/MODULE_NAME/Plugin/Custom/Directory/Path/ []: </question> ',
            'question_default_answer' => '',
        ];
        // Ask the user for the PLUGIN_CLASS_NAME, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::PLUGIN_CLASS_NAME] = [
            'name'                    => ConfigKey::PLUGIN_CLASS_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Plugin class name, e.g. ClassNamePlugin',
            'question'                => '<question>Plugin class name []: </question> ',
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
        $subfolderPath     = 'Plugin' . DIRECTORY_SEPARATOR . $payload['config'][$this->id]['values'][ConfigKey::PLUGIN_DIRECTORY_PATH];
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName  = ucfirst(
            str_replace(
                '{{PLUGIN_CLASS_NAME}}',
                $payload['config'][$this->id]['values'][ConfigKey::PLUGIN_CLASS_NAME],
                '{{PLUGIN_CLASS_NAME}}.php'
            )
        );
        $templateFilePath  = $this->template->getTemplateFilePath(
            '{{PLUGIN_CLASS_NAME}}.php.plugin-type-before.template',
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
        [$interceptedClassNamespace, $interceptedMethodName] = explode(
            '::',
            $payload['config'][$stageId]['values'][ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE]
        );
        $payload['config'][$stageId]['values'][ConfigKey::INTERCEPTED_CLASS_NAMESPACE] = $interceptedClassNamespace;
        $payload['config'][$stageId]['values'][ConfigKey::INTERCEPTED_METHOD_NAME]     = $interceptedMethodName;

        return [
            '{{VENDOR_NAME}}'                     => $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}'                     => $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{VENDOR_NAME_LOWERCASE}}'           => strtolower($payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]),
            '{{MODULE_NAME_LOWERCASE}}'           => strtolower($payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME]),
            '{{YEAR}}'                            => date('Y'),
            /**
             * @todo These kind of Command-specific template variables should be moved out of here
             *       This stage is for creating a di.xml file
             *       Updating the di.xml file to include command-specific template variables should be added to a new 'UpdateDiXmlFileStage'
             */
            '{{METHOD_TO_INTERCEPT_NAMESPACE}}'   => $payload['config'][$stageId]['values'][ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE],
            '{{PLUGIN_TYPE}}'                     => $payload['config'][$stageId]['values'][ConfigKey::PLUGIN_TYPE],
            '{{PLUGIN_CLASS_NAME}}'               => $payload['config'][$stageId]['values'][ConfigKey::PLUGIN_CLASS_NAME],
            '{{PLUGIN_CLASS_NAME_UCFIRST}}'       => ucfirst($payload['config'][$stageId]['values'][ConfigKey::PLUGIN_CLASS_NAME]),
            '{{PLUGIN_CLASS_NAME_STRTOLOWER}}'    => strtolower($payload['config'][$stageId]['values'][ConfigKey::PLUGIN_CLASS_NAME]),
            '{{INTERCEPTED_CLASS_NAMESPACE}}'     => $payload['config'][$stageId]['values'][ConfigKey::INTERCEPTED_CLASS_NAMESPACE],
            '{{INTERCEPTED_METHOD_NAME_UCFIRST}}' => ucfirst($payload['config'][$stageId]['values'][ConfigKey::INTERCEPTED_METHOD_NAME]),
        ];
    }
}
