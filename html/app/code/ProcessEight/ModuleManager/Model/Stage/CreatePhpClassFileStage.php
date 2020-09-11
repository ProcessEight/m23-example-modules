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

/**
 * @deprecated
 *
 * Class CreatePhpClassFileStage
 *
 * Creates a PHP class file.
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreatePhpClassFileStage extends BaseStage
{
    public $id = 'createPhpClassFileStage';

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
     * @param \ProcessEight\ModuleManager\Service\Folder     $folder
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

//    /**
//     * Called when this Pipeline is invoked by another Pipeline/Stage
//     *
//     * @param mixed[] $payload
//     *
//     * @return mixed[]
//     */
//    public function __invoke(array $payload) : array
//    {
//        if ($payload['is_valid'] === true) {
//            $payload = $this->processStage($payload);
//        }
//
//        return $payload;
//    }

    /**
     * @param mixed[] $payload
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

        // Pass payload onto next stage/pipeline
        return $payload;
    }

    /**
     * @todo Verify this can be removed
     */
//    /**
//     * @param string $templateFilePath
//     * @param array  $templateVariables
//     *
//     * @return string
//     * @throws FileSystemException
//     */
//    private function getProcessedTemplate(string $templateFilePath, array $templateVariables) : string
//    {
//        // Read template
//        $artefactFileTemplate = $this->filesystemDriver->fileGetContents($templateFilePath);
//
//        foreach ($templateVariables as $templateVariable => $value) {
//            $artefactFileTemplate = str_replace($templateVariable, $value, $artefactFileTemplate);
//        }
//
//        return $artefactFileTemplate;
//    }
}
