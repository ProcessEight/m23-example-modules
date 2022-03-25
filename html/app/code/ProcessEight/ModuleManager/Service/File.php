<?php

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Service;

use Magento\Framework\Exception\FileSystemException;

class File
{
    /**
     * @var Template
     */
    private $templateService;
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * File constructor.
     *
     * @param Template                                  $templateService
     * @param \Magento\Framework\Filesystem\Driver\File $filesystemDriver
     */
    public function __construct(
        \ProcessEight\ModuleManager\Service\Template $templateService,
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver
    ) {
        $this->templateService  = $templateService;
        $this->filesystemDriver = $filesystemDriver;
    }

    /**
     * Create file from template
     *
     * @param array  $payload
     * @param string $templateFilePath
     * @param array  $templateVariables
     * @param string $artefactFilePath
     * @param string $artefactFileName
     *
     * @return array
     */
    public function createFileFromTemplate(
        array $payload,
        string $templateFilePath,
        array $templateVariables,
        string $artefactFilePath,
        string $artefactFileName
    ) : array {
        try {
            // Read template
            $artefactFileTemplate = $this->templateService->getProcessedTemplate($templateFilePath, $templateVariables);

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

        $payload['messages'][] = "Created file at <info>" . $artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName . "</info>";

        return $payload;
    }
}
