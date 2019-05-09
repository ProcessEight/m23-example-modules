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
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 ProcessEight
 * @author      ProcessEight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Model\Stage;

use Magento\Framework\Exception\FileSystemException;

/**
 * Class CreatePhpClassFileStage
 *
 * Creates a PHP class file.
 *
 * Don't add any configuration for specific PHP classes here, that should be added to the relevant command which creates the class
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreatePhpClassFileStage
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $templateFilePath;

    /**
     * @var string[]
     */
    private $templateVariables;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Filesystem\Driver\File $filesystemDriver
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver
    ) {
        $this->filesystemDriver = $filesystemDriver;
    }

    /**
     * Called when this pipeline is invoked by another pipeline/stage (as opposed to being inject by DI)
     *
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function __invoke(array $payload) : array
    {
        if ($payload['is_valid'] === true) {
            $payload = $this->processStage($payload);
        }

        return $payload;
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    public function processStage(array $payload) : array
    {
        // Check if file exists
        try {
            $isExists = $this->filesystemDriver->isExists($this->getFilePath() . DIRECTORY_SEPARATOR . $this->getFileName());
            if ($isExists) {
                $payload['creation_message'][] = "<info>" . $this->getFileName() . "</info> file already exists at <info>{$this->getFilePath()}</info>";

                return $payload;
            }
        } catch (FileSystemException $e) {
            $payload['is_valid']           = false;
            $payload['creation_message'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        // Create file from template
        try {
            // Read template
            $artefactFileTemplate = $this->filesystemDriver->fileGetContents($this->getTemplateFilePath());

            foreach ($this->getTemplateVariables() as $templateVariable => $value) {
                $artefactFileTemplate = str_replace($templateVariable, $value, $artefactFileTemplate);
            }

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen(
                $this->getFilePath() . DIRECTORY_SEPARATOR . $this->getFileName(),
                'wb+'
            );
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);

        } catch (FileSystemException $e) {
            $payload['is_valid']           = false;
            $payload['creation_message'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        $payload['creation_message'][] = "Created <info>" . $this->getFileName() . "</info> file at <info>{$this->getFilePath()}</info>";

        return $payload;
    }

    /**
     * @return mixed[]
     */
    public function getFilePath() : string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath) : void
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getFileName() : string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName) : void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getTemplateFilePath() : string
    {
        return $this->templateFilePath;
    }

    /**
     * @param string $templateFilePath
     */
    public function setTemplateFilePath(string $templateFilePath) : void
    {
        $this->templateFilePath = $templateFilePath;
    }

    /**
     * @return string[]
     */
    public function getTemplateVariables() : array
    {
        return $this->templateVariables;
    }

    /**
     * @param string[] $templateVariables
     */
    public function setTemplateVariables(array $templateVariables) : void
    {
        $this->templateVariables = $templateVariables;
    }
}
