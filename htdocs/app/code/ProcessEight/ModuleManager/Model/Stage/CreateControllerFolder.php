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
 * Class CreateControllerFolder
 *
 * Creates a directory structure in the format Controller/<Adminhtml>/<controller-directory-name>/
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateControllerFolder
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
     * Constructor.
     *
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Driver\File       $filesystemDriver
     */
    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver
    ) {
        $this->directoryList    = $directoryList;
        $this->filesystemDriver = $filesystemDriver;
    }

    /**
     * @param mixed[] $config
     *
     * @return mixed[]
     * @throws FileSystemException
     */
    public function __invoke(array $config)
    {
        // Get absolute path to controller folder
        $moduleEtcPath = implode(DIRECTORY_SEPARATOR, [
            $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP),
            'code',
            $config['data'][ConfigKey::VENDOR_NAME],
            $config['data'][ConfigKey::MODULE_NAME],
            \Magento\Framework\Module\Dir::MODULE_CONTROLLER_DIR,
            ($config['data']['area-code'] === 'adminhtml' ? ucfirst($config['data']['area-code']) : ''),
            ucfirst($config['data'][ConfigKey::CONTROLLER_DIRECTORY_NAME]),
        ]);

        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($moduleEtcPath);
        } catch (FileSystemException $e) {
            $config['messages'][] = "Failed checking folder exists at <info>{$moduleEtcPath}</info>: " . ($e->getMessage());

            return $config;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($moduleEtcPath);
        } catch (FileSystemException $e) {
            $config['messages'][] = "Failed to create folder at <info>'{$moduleEtcPath}'</info> with default permissions of '<info>0777</info>'" . $e->getMessage();

            return $config;
        }
        $config['messages'][] = "Created Controller folder at <info>{$moduleEtcPath}</info>";

        return $config;
    }
}
