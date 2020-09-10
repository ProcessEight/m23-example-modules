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
 * Class CreateCommandFolderStage
 *
 * This stage creates the folder {{VENDOR_NAME}}/{{MODULE_NAME}}/Command/
 *
 */
class CreateCommandFolderStage extends BaseStage
{
    public $id = 'createCommandFolderStage';

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @todo Refactor this class into Service folder
     * @var \ProcessEight\ModuleManager\Model\Folder
     */
    private $folder;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Filesystem\Driver\File $filesystemDriver
     * @param \ProcessEight\ModuleManager\Model\Folder  $folder
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \ProcessEight\ModuleManager\Model\Folder $folder
    ) {
        $this->filesystemDriver = $filesystemDriver;
        $this->folder           = $folder;
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $commandFolderPath = $this->folder->getAbsolutePathToFolder($payload, $this->id, 'Command');

        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($commandFolderPath);
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();
            $payload['messages'][] = "Check if folder exists at <info>{$commandFolderPath}</info>";

            return $payload;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($commandFolderPath);
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();
            $payload['messages'][] = "Failed to create folder at <info>'{$commandFolderPath}'</info> with default permissions of '<info>0777</info>'";

            return $payload;
        }
        $payload['messages'][] = "Created folder at <info>{$commandFolderPath}</info>";

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}
