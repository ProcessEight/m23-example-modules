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
     * Called when this pipeline is invoked by another pipeline/stage (as opposed to being injected by DI)
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
        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($payload['config']['create-folder-stage']['folder-path']);
        } catch (FileSystemException $e) {
            $payload['messages'][] = __METHOD__ . ": Check if folder exists at <info>{$payload['config']['create-folder-stage']['folder-path']}</info>: " . ($e->getMessage());

            return $payload;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($payload['config']['create-folder-stage']['folder-path']);
        } catch (FileSystemException $e) {
            $payload['messages'][] = "Failed to create folder at <info>'{$payload['config']['create-folder-stage']['folder-path']}'</info> with default permissions of '<info>0777</info>'"
                                             . $e->getMessage();
            $payload['is_valid']           = false;

            return $payload;
        }
        $payload['messages'][] = "Created folder at <info>{$payload['config']['create-folder-stage']['folder-path']}</info>";

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}
