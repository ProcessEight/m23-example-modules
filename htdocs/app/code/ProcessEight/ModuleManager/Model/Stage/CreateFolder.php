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

class CreateFolder
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
     * Constructor
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
     */
    public function __invoke(array $config) : array
    {
        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($config['data']['path-to-folder']);
        } catch (FileSystemException $e) {
            $config['creation_message'][] = "Check if folder exists at <info>{$config['data']['path-to-folder']}</info>: " . ($e->getMessage());

            return $config;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($config['data']['path-to-folder']);
        } catch (FileSystemException $e) {
            $config['creation_message'][] = "Failed to create folder at <info>'{$config['data']['path-to-folder']}'</info> with default permissions of '<info>0777</info>'" . $e->getMessage();
            $config['is_valid']           = false;

            return $config;
        }
        $config['creation_message'][] = "Created folder at <info>{$config['data']['path-to-folder']}</info>";

        return $config;
    }
}
