<?php
/**
 * Process Eight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 Process Eight
 * @author      Process Eight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Model\Stage;

use Magento\Framework\Exception\FileSystemException;

/**
 * Creates a vendor-name/module-name/etc/module.xml file
 * Assumes that the vendor-name/module-name/etc/ folder already exists
 */
class CreateModuleXmlFile
{
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
        // Get absolute path to module etc folder
        try {
            $moduleEtcPath = implode(DIRECTORY_SEPARATOR, [
                $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP),
                'code',
                $config['data']['vendor-name'],
                $config['data']['module-name'],
                \Magento\Framework\Module\Dir::MODULE_ETC_DIR,
            ]);
        } catch (FileSystemException $e) {
            $config['is_valid']         = false;
            $config['creation_message'][] = "Failed getting absolute path to etc folder: " . ($e->getMessage());

            return $config;
        }

        // Check if folder exists
        $moduleXmlFilePath = $moduleEtcPath . DIRECTORY_SEPARATOR . 'module.xml';
        try {
            $isExists = $this->filesystemDriver->isExists($moduleXmlFilePath);
            if($isExists) {
                $config['creation_message'][] = "<info>module.xml</info> file already exists at <info>{$moduleXmlFilePath}</info>";

                return $config;
            }
        } catch (FileSystemException $e) {
            $config['is_valid']         = false;
            $config['creation_message'][] = "Failed checking folder exists at <info>{$moduleEtcPath}</info>: " . ($e->getMessage());

            return $config;
        }

        // Create file from template
        try {
            // Read template
            $moduleXmlFileTemplate = $this->filesystemDriver->fileGetContents(
                $this->moduleDir->getDir('ProcessEight_ModuleManager') . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . 'module.xml.template'
            );
            $moduleXmlFileTemplate = str_replace('{{VENDOR_NAME}}', $config['data']['vendor-name'],
                $moduleXmlFileTemplate);
            $moduleXmlFileTemplate = str_replace('{{MODULE_NAME}}', $config['data']['module-name'],
                $moduleXmlFileTemplate);
            $moduleXmlFileTemplate = str_replace('{{YEAR}}', date('Y'), $moduleXmlFileTemplate);

            // Write template to file
            $moduleXmlFileResource = $this->filesystemDriver->fileOpen($moduleXmlFilePath,
                'wb+');
            $this->filesystemDriver->fileWrite($moduleXmlFileResource, $moduleXmlFileTemplate);

        } catch (FileSystemException $e) {
            $config['is_valid']         = false;
            $config['creation_message'][] = "Failed to create folder at <info>'{$moduleEtcPath}'</info> with default permissions of '<info>0777</info>'" . $e->getMessage();

            return $config;
        }

        $config['creation_message'][] = "Created <info>module.xml</info> file at <info>{$moduleXmlFilePath}</info>";
        return $config;
    }
}
