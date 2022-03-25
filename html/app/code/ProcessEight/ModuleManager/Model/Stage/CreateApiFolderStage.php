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
 * @copyright   Copyright (c) 2020 ProcessEight
 * @author      ProcessEight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Model\Stage;

use Magento\Framework\Exception\FileSystemException;

/**
 * Class CreateApiFolderStage
 *
 * Creates an etc folder
 *
 */
class CreateApiFolderStage extends \ProcessEight\ModuleManager\Model\Stage\BaseStage
{
    /**
     * @var string
     */
    public $id = 'createApiFolderStage';

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var \ProcessEight\ModuleManager\Service\Folder
     */
    private $folder;

    /**
     * CreateApiFolderStage constructor.
     *
     * @param \Magento\Framework\Filesystem\Driver\File  $filesystemDriver
     * @param \ProcessEight\ModuleManager\Service\Folder $folder
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
        $absolutePathToFolder = $this->folder->getAbsolutePathToFolder(
            $payload,
            $this->id,
            'Api'
        );

        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($absolutePathToFolder);
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($absolutePathToFolder);
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        $payload['messages'][] = "Created folder at <info>" . $absolutePathToFolder . "</info>";

        // Pass payload onto next Stage/Pipeline
        return $payload;
    }
}
