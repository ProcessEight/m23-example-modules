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

namespace ProcessEight\ModuleManager\Model\Stage\Frontend;

use Magento\Framework\Exception\FileSystemException;
use ProcessEight\ModuleManager\Model\ConfigKey;

/**
 * Class CreateFrontendRoutesXmlFileStage
 *
 * Creates a vendor-name/module-name/etc/frontend/routes.xml file
 * Assumes that the vendor-name/module-name/etc/frontend folder already exists
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateFrontendRoutesXmlFileStage extends \ProcessEight\ModuleManager\Model\Stage\BaseStage
{
    public $id = 'createFrontendRoutesXmlFileStage';

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var \Magento\Framework\Module\Dir
     */
    private $moduleDir;

    /**
     * @var \ProcessEight\ModuleManager\Service\Folder
     */
    private $folder;

    /**
     * @var \ProcessEight\ModuleManager\Service\Template
     */
    private $template;

    /**
     * CreateModuleFolder constructor.
     *
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Driver\File       $filesystemDriver
     * @param \Magento\Framework\Module\Dir                   $moduleDir
     * @param \ProcessEight\ModuleManager\Service\Folder      $folder
     * @param \ProcessEight\ModuleManager\Service\Template    $template
     */
    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \Magento\Framework\Module\Dir $moduleDir,
        \ProcessEight\ModuleManager\Service\Folder $folder,
        \ProcessEight\ModuleManager\Service\Template $template
    ) {
        $this->directoryList    = $directoryList;
        $this->filesystemDriver = $filesystemDriver;
        $this->moduleDir        = $moduleDir;
        $this->folder           = $folder;
        $this->template         = $template;
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    public function configureStage(array $payload) : array
    {
        // Ask the user for the front name, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::FRONT_NAME]                = [
            'name'                    => ConfigKey::FRONT_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'description'             => 'Front name',
            'question'                => '<question>Front name (the \'catalog\' in \'/catalog/product/edit\'): [custom]</question>',
            'question_default_answer' => 'custom',
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
        $subfolderPath     = 'etc' . DIRECTORY_SEPARATOR . 'frontend';
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName  = 'routes.xml';
        $templateFilePath  = $this->template->getTemplateFilePath($artefactFileName . '.template', $subfolderPath);
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
            '{{VENDOR_NAME}}'                       => $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}'                       => $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{VENDOR_NAME_LOWERCASE}}'             => strtolower($payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]),
            '{{MODULE_NAME_LOWERCASE}}'             => strtolower($payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME]),
            '{{YEAR}}'                              => date('Y'),
            /**
             * @todo These kind of Command-specific template variables should be moved out of here
             *       This stage is for creating a di.xml file
             *       Updating the di.xml file to include command-specific template variables should be added to a new 'UpdateDiXmlFileStage'
             */
            '{{FRONT_NAME}}'                        => $payload['config'][$stageId]['values'][ConfigKey::FRONT_NAME],
            '{{CONTROLLER_DIRECTORY_NAME}}'         => $payload['config'][$stageId]['values'][ConfigKey::CONTROLLER_DIRECTORY_NAME],
            '{{CONTROLLER_DIRECTORY_NAME_UCFIRST}}' => ucfirst($payload['config'][$stageId]['values'][ConfigKey::CONTROLLER_DIRECTORY_NAME]),
            '{{CONTROLLER_ACTION_NAME}}'            => $payload['config'][$stageId]['values'][ConfigKey::CONTROLLER_ACTION_NAME],
            '{{CONTROLLER_ACTION_NAME_UCFIRST}}'    => ucfirst($payload['config'][$stageId]['values'][ConfigKey::CONTROLLER_ACTION_NAME]),
        ];
    }
}