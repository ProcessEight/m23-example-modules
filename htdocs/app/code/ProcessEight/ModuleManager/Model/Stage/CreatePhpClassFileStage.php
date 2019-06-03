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
     * Called when this Pipeline is invoked by another Pipeline/Stage
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
            $artefactFileName = $payload['config']['create-php-class-file-stage']['file-name'];

            $isExists = $this->filesystemDriver->isExists($payload['config']['create-php-class-file-stage']['file-path'] . DIRECTORY_SEPARATOR . $artefactFileName);
            if ($isExists) {
                $payload['messages'][] = "<info>" . $artefactFileName . "</info> file already exists at <info>{$payload['config']['create-php-class-file-stage']['file-path']}</info>";

                return $payload;
            }
        } catch (FileSystemException $e) {
            $payload['is_valid']           = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        // Create file from template
        try {
            // Read template
            $artefactFileTemplate = $this->filesystemDriver->fileGetContents($payload['config']['create-php-class-file-stage']['template-file-path']);

            foreach ($payload['config']['create-php-class-file-stage']['template-variables'] as $templateVariable => $value) {
                $artefactFileTemplate = str_replace($templateVariable, $value, $artefactFileTemplate);
            }

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen(
                $payload['config']['create-php-class-file-stage']['file-path'] . DIRECTORY_SEPARATOR . $artefactFileName,
                'wb+'
            );
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);

        } catch (FileSystemException $e) {
            $payload['is_valid']           = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        $payload['messages'][] = "Created <info>" . $artefactFileName . "</info> file at <info>{$payload['config']['create-php-class-file-stage']['file-path']}</info>";

        return $payload;
    }
}
