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
use ProcessEight\ModuleManager\Model\ConfigKey;

/**
 * Class CreateEtcFolderStage
 *
 * Creates an etc folder
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateEtcFolderStage extends BaseStage
{
    public $id = 'createEtcFolderStage';

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * CreateModuleFolder constructor.
     *
     * @param \Magento\Framework\Filesystem\Driver\File       $filesystemDriver
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->filesystemDriver = $filesystemDriver;
        $this->directoryList    = $directoryList;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $folderPath = $this->getAbsolutePathToFolder($payload, 'etc');

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

    /**
     * @param array  $payload
     * @param string $subfolderPath
     *
     * @return string
     * @throws FileSystemException
     */
    private function getAbsolutePathToFolder(
        array $payload,
        string $subfolderPath = ''
    ) : string {
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP)
               . DIRECTORY_SEPARATOR . 'code'
               . DIRECTORY_SEPARATOR . $payload['config'][$this->id]['values'][ConfigKey::VENDOR_NAME]
               . DIRECTORY_SEPARATOR . $payload['config'][$this->id]['values'][ConfigKey::MODULE_NAME]
               . DIRECTORY_SEPARATOR . $subfolderPath;
    }
}
