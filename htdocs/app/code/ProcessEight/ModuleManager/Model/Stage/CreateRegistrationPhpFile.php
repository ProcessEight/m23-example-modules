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
use ProcessEight\ModuleManager\Model\ConfigKey;

/**
 * Creates a vendor-name/module-name/registration.php file
 * Assumes that the vendor-name/module-name/ folder already exists
 */
class CreateRegistrationPhpFile
{
    const ARTEFACT_FILE_NAME = 'registration.php';

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
     * CreateModuleFolder constructor.
     *
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Driver\File       $filesystemDriver
     * @param \Magento\Framework\Module\Dir                   $moduleDir
     */
    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \Magento\Framework\Module\Dir $moduleDir
    ) {
        $this->directoryList    = $directoryList;
        $this->filesystemDriver = $filesystemDriver;
        $this->moduleDir        = $moduleDir;
    }

    /**
     * @param mixed[] $config
     *
     * @return mixed[]
     */
    public function __invoke(array $config)
    {
        // Get absolute path to module folder
        try {
            $modulePath = implode(DIRECTORY_SEPARATOR, [
                $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP),
                'code',
                $config['data'][ConfigKey::VENDOR_NAME],
                $config['data'][ConfigKey::MODULE_NAME],
            ]);
        } catch (FileSystemException $e) {
            $config['is_valid']           = false;
            $config['messages'][] = "Failed getting absolute path to module folder: " . ($e->getMessage());

            return $config;
        }

        // Check if file exists
        $artefactFilePath = $modulePath . DIRECTORY_SEPARATOR . self::ARTEFACT_FILE_NAME;
        try {
            $isExists = $this->filesystemDriver->isExists($artefactFilePath);
            if ($isExists) {
                $config['messages'][] = "<info>" . self::ARTEFACT_FILE_NAME . "</info> file already exists at <info>{$artefactFilePath}</info>";

                return $config;
            }
        } catch (FileSystemException $e) {
            $config['is_valid']           = false;
            $config['messages'][] = "Failed checking folder exists at <info>{$modulePath}</info>: " . ($e->getMessage());

            return $config;
        }

        // Create file from template
        try {
            // Read template
            $artefactFileTemplate = $this->filesystemDriver->fileGetContents(implode(DIRECTORY_SEPARATOR , [
                $this->moduleDir->getDir('ProcessEight_ModuleManager'),
                'Template',
                self::ARTEFACT_FILE_NAME . '.template',
            ]));
            $artefactFileTemplate = str_replace('{{VENDOR_NAME}}', $config['data'][ConfigKey::VENDOR_NAME], $artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{MODULE_NAME}}', $config['data'][ConfigKey::MODULE_NAME], $artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{YEAR}}', date('Y'), $artefactFileTemplate);

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen($artefactFilePath,
                'wb+');
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);

        } catch (FileSystemException $e) {
            $config['is_valid']           = false;
            $config['messages'][] = "Failure: " . $e->getMessage();

            return $config;
        }
        $config['messages'][] = "Created <info>" . self::ARTEFACT_FILE_NAME . "</info> file at <info>{$artefactFilePath}</info>";

        return $config;
    }
}
