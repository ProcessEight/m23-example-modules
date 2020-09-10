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
use ProcessEight\ModuleManager\Service\Folder;

/**
 * Class CreateModuleFolderStage
 *
 * Creates the module folder
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateModuleFolderStage extends BaseStage
{
    public $id = 'createModuleFolderStage';

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var Folder
     */
    private $folder;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Filesystem\Driver\File $filesystemDriver
     * @param \ProcessEight\ModuleManager\Service\Folder  $folder
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \ProcessEight\ModuleManager\Service\Folder $folder
    ) {
        $this->filesystemDriver = $filesystemDriver;
        $this->folder           = $folder;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $moduleFolderPath = $this->folder->getAbsolutePathToFolder($payload, $this->id);

        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($moduleFolderPath);
        } catch (FileSystemException $e) {
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($moduleFolderPath);
        } catch (FileSystemException $e) {
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        $payload['messages'][] = "Created module folder at <info>{$moduleFolderPath}</info>";

        // Pass payload onto next Stage/Pipeline
        return $payload;
    }
}
