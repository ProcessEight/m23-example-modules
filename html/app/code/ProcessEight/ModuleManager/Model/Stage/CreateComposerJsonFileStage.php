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
 * Class CreateComposerJsonFileStage
 *
 * Creates a vendor-name/module-name/composer.json file
 * Assumes that the vendor-name/module-name/ folder already exists
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateComposerJsonFileStage extends BaseStage
{
    public $id = 'createComposerJsonFileStage';

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
     * CreateModuleFolder constructor.
     *
     * @param \Magento\Framework\Filesystem\Driver\File       $filesystemDriver
     * @param \ProcessEight\ModuleManager\Model\Folder        $folder
     * @param \ProcessEight\ModuleManager\Service\Template    $template
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
     * @param array $payload
     *
     * @return mixed[]
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id);
        $artefactFileName  = $payload['config'][$this->id]['values']['file-name'];
        $templateFilePath  = $this->template->getTemplateFilePath($artefactFileName);
        $templateVariables = $payload['config'][$this->id]['values']['template-variables'];

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

        return $payload;
    }
}
