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

class CreateEtcFolder
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
     * CreateModuleFolder constructor.
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
        // Get absolute path to module etc folder
        $moduleEtcPath = implode(DIRECTORY_SEPARATOR, [
            $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP),
            'code',
            $config['data']['vendor-name'],
            $config['data']['module-name'],
            \Magento\Framework\Module\Dir::MODULE_ETC_DIR,
        ]);

        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($moduleEtcPath);
        } catch (FileSystemException $e) {
            $config['creation_message'] = "Failed checking folder exists at <info>{$moduleEtcPath}</info>: " . ($e->getMessage());

            return $config;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($moduleEtcPath);
        } catch (FileSystemException $e) {
            $config['creation_message'] = "Failed to create folder at <info>'{$moduleEtcPath}'</info> with default permissions of '<info>0777</info>'" . $e->getMessage();

            return $config;
        }

        return $config;
    }
}
