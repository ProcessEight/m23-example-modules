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

namespace ProcessEight\ModuleManager\Command;

use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class BaseCommand
 *
 * Contains common logic for creating Pipeline commands
 *
 * @package ProcessEight\ModuleManager\Command
 */
class BaseCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * Used to generate file name
     */
    public $artefactFileName;

    /**
     * Area code this command is working with
     */
    public $areaCode = 'frontend';

    /**
     * @var \League\Pipeline\Pipeline
     */
    public $masterPipeline;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                       $masterPipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \League\Pipeline\Pipeline $masterPipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        parent::__construct();
        $this->masterPipeline = $masterPipeline;
        $this->directoryList  = $directoryList;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string                                          $trailingPath
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getAbsolutePathToFolder(
        \Symfony\Component\Console\Input\InputInterface $input,
        string $trailingPath = ''
    ) : string {
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP) .
               DIRECTORY_SEPARATOR . 'code' .
               DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::VENDOR_NAME) .
               DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::MODULE_NAME) .
               DIRECTORY_SEPARATOR . $trailingPath;
    }

//    /**
//     * All template variables used in all Stages/Pipelines used by this command
//     *
//     * @param InputInterface $input
//     *
//     * @return array
//     */
//    public function getTemplateVariables(\Symfony\Component\Console\Input\InputInterface $input) : array
//    {
//        return [
//            '{{VENDOR_NAME}}'           => $input->getOption(ConfigKey::VENDOR_NAME),
//            '{{MODULE_NAME}}'           => $input->getOption(ConfigKey::MODULE_NAME),
//            '{{VENDOR_NAME_LOWERCASE}}' => strtolower($input->getOption(ConfigKey::VENDOR_NAME)),
//            '{{MODULE_NAME_LOWERCASE}}' => strtolower($input->getOption(ConfigKey::MODULE_NAME)),
//            '{{YEAR}}'                  => date('Y'),
//        ];
//    }

    /**
     * Return path to the template file
     *
     * @param string $fileName     File name has '.template' appended
     * @param string $trailingPath Sub-folder within Template folder (if any) which contains the template file
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getTemplateFilePath(string $fileName, string $trailingPath = '') : string
    {
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP) .
               DIRECTORY_SEPARATOR . 'code' .
               DIRECTORY_SEPARATOR . 'ProcessEight' .
               DIRECTORY_SEPARATOR . 'ModuleManager' .
               DIRECTORY_SEPARATOR . 'Template' .
               DIRECTORY_SEPARATOR . $trailingPath .
               DIRECTORY_SEPARATOR . $fileName . '.template';
    }
}
