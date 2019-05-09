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
 * Class CreateMenuXmlFile
 *
 * Creates a vendor-name/module-name/etc/<area-code>/menu.xml file
 * Assumes that the vendor-name/module-name/etc/<area-code> folder already exists
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateMenuXmlFile
{
    const ARTEFACT_FILE_NAME = 'menu.xml';

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
     * Constructor.
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
        // Get absolute path to module etc folder
        try {
            $moduleEtcPath = implode(DIRECTORY_SEPARATOR, [
                $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP),
                'code',
                $config['data'][ConfigKey::VENDOR_NAME],
                $config['data'][ConfigKey::MODULE_NAME],
                \Magento\Framework\Module\Dir::MODULE_ETC_DIR,
                $config['data']['area-code'],
            ]);
        } catch (FileSystemException $e) {
            $config['is_valid']           = false;
            $config['creation_message'][] = "Failed getting absolute path to etc folder: " . ($e->getMessage());

            return $config;
        }

        // Check if folder exists
        $artefactFilePath = $moduleEtcPath . DIRECTORY_SEPARATOR . self::ARTEFACT_FILE_NAME;
        try {
            $isExists = $this->filesystemDriver->isExists($artefactFilePath);
            if ($isExists) {
                $config['creation_message'][] = "<info>" . self::ARTEFACT_FILE_NAME . "</info> file already exists at <info>{$artefactFilePath}</info>";

                return $config;
            }
        } catch (FileSystemException $e) {
            $config['is_valid']           = false;
            $config['creation_message'][] = "Failed checking folder exists at <info>{$moduleEtcPath}</info>: " . ($e->getMessage());

            return $config;
        }

        // Create file from template
        try {
            // Read template
            $artefactFileTemplate = $this->filesystemDriver->fileGetContents(implode(DIRECTORY_SEPARATOR, [
                    $this->moduleDir->getDir('ProcessEight_ModuleManager'),
                    'Template',
                    'etc',
                    $config['data']['area-code'],
                    self::ARTEFACT_FILE_NAME . '.template',
                ]
            ));
            $artefactFileTemplate = str_replace('{{VENDOR_NAME}}', $config['data'][ConfigKey::VENDOR_NAME],$artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{MODULE_NAME}}', $config['data'][ConfigKey::MODULE_NAME],$artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{FRONT_NAME}}', $config['data']['front-name'], $artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{CONTROLLER_DIRECTORY_NAME}}',strtolower($config['data'][ConfigKey::CONTROLLER_DIRECTORY_NAME]), $artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{CONTROLLER_ACTION_NAME}}',strtolower($config['data'][ConfigKey::CONTROLLER_ACTION_NAME]), $artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{YEAR}}', date('Y'), $artefactFileTemplate);

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen($artefactFilePath, 'wb+');
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);

        } catch (FileSystemException $e) {
            $config['is_valid']           = false;
            $config['creation_message'][] = "Failure: " . $e->getMessage();

            return $config;
        }
        $config['creation_message'][] = "Created <info>" . self::ARTEFACT_FILE_NAME . "</info> file at <info>{$artefactFilePath}</info>";

        return $config;
    }
}
