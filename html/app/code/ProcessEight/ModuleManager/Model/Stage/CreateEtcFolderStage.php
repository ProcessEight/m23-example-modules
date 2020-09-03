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
 * Class CreateEtcFolderStage
 *
 * Creates an etc folder
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateEtcFolderStage extends BaseStage
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
        $folderPath = $payload['config']['create-etc-folder-stage']['folder-path'];

        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($folderPath);
        } catch (FileSystemException $e) {
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($folderPath);
        } catch (FileSystemException $e) {
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        $payload['messages'][] = "Created etc folder at <info>{$folderPath}</info>";

        // Pass payload onto next Stage/Pipeline
        return $payload;
    }
}
