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
 * Class CreateFolderStage
 *
 * This stage creates a folder in the given location
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateFolderStage
{
    /**
     * @var mixed[]
     */
    private $folderPath;

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
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function __invoke(array $payload) : array
    {
        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($this->folderPath);
        } catch (FileSystemException $e) {
            $payload['creation_message'][] = "Check if folder exists at <info>{$this->folderPath}</info>: " . ($e->getMessage());

            return $payload;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($this->folderPath);
        } catch (FileSystemException $e) {
            $payload['creation_message'][] = "Failed to create folder at <info>'{$this->folderPath}'</info> with default permissions of '<info>0777</info>'" . $e->getMessage();
            $payload['is_valid']           = false;

            return $payload;
        }
        $payload['creation_message'][] = "Created folder at <info>{$this->folderPath}</info>";

        // Pass payload onto next stage/pipeline
        return $payload;
    }

    /**
     * @param string $folderPath
     */
    public function setFolderPath(string $folderPath) : void
    {
        $this->folderPath = $folderPath;
    }
}
