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
 * Class CreateRegistrationPhpFileStage
 *
 * Creates a vendor-name/module-name/registration.php file
 * Assumes that the vendor-name/module-name/ folder already exists
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateRegistrationPhpFileStage extends BaseStage
{
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * CreateModuleFolder constructor.
     *
     * @param \Magento\Framework\Filesystem\Driver\File $filesystemDriver
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver
    ) {
        $this->filesystemDriver = $filesystemDriver;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function processStage(array $payload) : array
    {
        $filePath          = $payload['config']['create-registration-php-file-stage']['file-path'];
        $fileName          = $payload['config']['create-registration-php-file-stage']['file-name'];
        $templateFilePath  = $payload['config']['create-registration-php-file-stage']['template-file-path'];
        $templateVariables = $payload['config']['create-registration-php-file-stage']['template-variables'];

        // Check if file exists
        try {
            $isExists = $this->filesystemDriver->isExists($filePath . DIRECTORY_SEPARATOR . $fileName);
            if ($isExists) {
                $payload['messages'][] = "<info>" . $fileName . "</info> file already exists at <info>{$filePath}</info>";

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
            $artefactFileTemplate = $this->filesystemDriver->fileGetContents($templateFilePath);

            foreach ($templateVariables as $templateVariable => $value) {
                $artefactFileTemplate = str_replace($templateVariable, $value, $artefactFileTemplate);
            }

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen(
                $filePath . DIRECTORY_SEPARATOR . $fileName,
                'wb+'
            );
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }
        $payload['messages'][] = "Created <info>" . $fileName . "</info> file at <info>{$filePath}</info>";

        return $payload;
    }
}
