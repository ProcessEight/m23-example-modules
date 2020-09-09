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

/**
 * Class CreateXmlFileStage
 *
 * Creates an XML file in the given location
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateXmlFileStage extends BaseStage
{
    /**
     * Override me in child classes
     * 'createXmlFileStage' should never actually appear in payload[config].
     * If it does, it most likely means a stage class is missing this property
     *
     * @var string
     */
    public $id = 'createXmlFileStage';

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \ProcessEight\ModuleManager\Model\Folder
     */
    private $folder;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Filesystem\Driver\File       $filesystemDriver
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \ProcessEight\ModuleManager\Model\Folder        $folder
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Folder $folder
    ) {
        $this->filesystemDriver = $filesystemDriver;
        $this->directoryList    = $directoryList;
        $this->folder           = $folder;
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $subfolderPath     = $payload['config'][$this->id]['values']['subfolder-path'];
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName  = $payload['config'][$this->id]['values']['file-name'];
        $templateFilePath  = $this->getTemplateFilePath($artefactFileName, $subfolderPath);
        $templateVariables = $payload['config'][$this->id]['values']['template-variables'];

        // Check if file exists
        try {
            $isExists = $this->filesystemDriver->isExists($artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);
            if ($isExists) {
                $payload['messages'][] = "<info>" . $artefactFileName . "</info> file already exists at <info>{$artefactFilePath}</info>";
                $payload['messages'][] = "<info>TODO: Add logic to modify existing files. For now, copy and paste the following into " . $artefactFileName . "</info>";
                $payload['messages'][] = "<info>" .
                                         $this->getProcessedTemplate($templateFilePath, $templateVariables) .
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
            $artefactFileTemplate = $this->getProcessedTemplate($templateFilePath, $templateVariables);

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

        return $payload;
    }

    /**
     * Return path to the template file
     *
     * @param string $fileName      File name has '.template' appended
     * @param string $subfolderPath Sub-folder within Template folder (if any) which contains the template file
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getTemplateFilePath(string $fileName, string $subfolderPath = '') : string
    {
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP) .
               DIRECTORY_SEPARATOR . 'code' .
               DIRECTORY_SEPARATOR . 'ProcessEight' .
               DIRECTORY_SEPARATOR . 'ModuleManager' .
               DIRECTORY_SEPARATOR . 'Template' .
               DIRECTORY_SEPARATOR . $subfolderPath .
               DIRECTORY_SEPARATOR . $fileName . '.template';
    }

    /**
     * @param string $templateFilePath
     * @param array  $templateVariables
     *
     * @return string
     * @throws FileSystemException
     */
    private function getProcessedTemplate(string $templateFilePath, array $templateVariables) : string
    {
        // Read template
        $artefactFileTemplate = $this->filesystemDriver->fileGetContents($templateFilePath);

        foreach ($templateVariables as $templateVariable => $value) {
            $artefactFileTemplate = str_replace($templateVariable, $value, $artefactFileTemplate);
        }

        return $artefactFileTemplate;
    }
}
