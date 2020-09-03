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
 * Class CreateAreaCodeFolder
 *
 * Creates a folder with the name <path-to-area-code-folder>/<area-code>
 * Assumes that <path-to-area-code-folder> already exists
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateAreaCodeFolder
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
     * @param mixed[] $config
     *
     * @return mixed[]
     * @throws FileSystemException
     */
    public function __invoke(array $config) : array
    {
        // Get absolute path to module <path-to-area-code-folder>/<area-code> folder
        $areaCodeFolderPath = str_replace('{{AREA_CODE}}', $config['config']['create-area-code-folder-stage']['area-code'], $config['config']['create-area-code-folder-stage']['path-to-area-code-folder']);

        // Check if folder exists
        $isExists = $this->filesystemDriver->isExists($areaCodeFolderPath);
        if ($isExists) {
            $config['messages'][] = "Folder already exists: <info>" . $areaCodeFolderPath . "</info>.";

            return $config;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($areaCodeFolderPath);
        } catch (FileSystemException $e) {
            $config['messages'][] = "Failed to create folder at <info>'{$areaCodeFolderPath}'</info> with default permissions of '<info>0777</info>'" . $e->getMessage();

            return $config;
        }
        $config['messages'][] = "Created folder at <info>'{$areaCodeFolderPath}'</info>";

        return $config;
    }
}
