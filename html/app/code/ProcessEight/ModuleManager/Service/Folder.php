<?php

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Service;

use Magento\Framework\Exception\FileSystemException;
use ProcessEight\ModuleManager\Model\ConfigKey;

class Folder
{
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    public $directoryList;

    /**
     * Folder constructor.
     *
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(\Magento\Framework\App\Filesystem\DirectoryList $directoryList)
    {
        $this->directoryList = $directoryList;
    }

    /**
     * @param array  $payload
     * @param string $stageId
     * @param string $subfolderPath
     *
     * @return string
     * @throws FileSystemException
     */
    public function getAbsolutePathToFolder(
        array $payload,
        string $stageId,
        string $subfolderPath = ''
    ) : string {
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP)
               . DIRECTORY_SEPARATOR . 'code'
               . DIRECTORY_SEPARATOR . $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]
               . DIRECTORY_SEPARATOR . $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME]
               . DIRECTORY_SEPARATOR . $subfolderPath;
    }
}