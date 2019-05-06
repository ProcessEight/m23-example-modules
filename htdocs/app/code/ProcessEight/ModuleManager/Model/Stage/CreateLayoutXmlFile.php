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
 * Creates a <layout-xml-handle>.xml file
 * Assumes that the vendor-name/module-name/view/<area-code>/layout/ folder already exists
 */
class CreateLayoutXmlFile
{
    const ARTEFACT_FILE_NAME = '{{LAYOUT_XML_HANDLE}}.xml';

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
        // Get absolute path to folder
        $absolutePathToFolder = str_replace('{{AREA_CODE}}', $config['data']['area-code'], $config['data']['path-to-area-code-folder']) . DIRECTORY_SEPARATOR;

        // Replace template variable in file name
        $artefactFileName = str_replace(
            '{{LAYOUT_XML_HANDLE}}',
            // Replace backslashes with underscores
            str_replace('/', '_', $config['data'][ConfigKey::LAYOUT_XML_HANDLE]),
            self::ARTEFACT_FILE_NAME
        );

        // Check if folder exists
        $artefactFilePath = $absolutePathToFolder . $artefactFileName;
        try {
            $isExists = $this->filesystemDriver->isExists($artefactFilePath);
            if($isExists) {
                $config['creation_message'][] = "File already exists: <info>{$artefactFilePath}</info>";

                return $config;
            }
        } catch (FileSystemException $e) {
            $config['is_valid']         = false;
            $config['creation_message'][] = "Failed checking folder exists at <info>{$artefactFilePath}</info>: " . ($e->getMessage());

            return $config;
        }

        // Create file from template
        try {
            // Read template
            $artefactFileTemplate = $this->filesystemDriver->fileGetContents(implode(DIRECTORY_SEPARATOR , [
                $this->moduleDir->getDir('ProcessEight_ModuleManager'),
                'Template',
                \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
                $config['data']['area-code'],
                'layout',
                self::ARTEFACT_FILE_NAME . '.template',
            ]));
            $artefactFileTemplate = str_replace('{{VENDOR_NAME}}', $config['data'][ConfigKey::VENDOR_NAME],$artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{MODULE_NAME}}', $config['data'][ConfigKey::MODULE_NAME],$artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{YEAR}}', date('Y'), $artefactFileTemplate);

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen($artefactFilePath,
                'wb+');
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);

        } catch (FileSystemException $e) {
            $config['is_valid']           = false;
            $config['creation_message'][] = "Failure: " . $e->getMessage();

            return $config;
        }

        $config['creation_message'][] = "Created file at <info>{$artefactFilePath}</info>";

        return $config;
    }
}
