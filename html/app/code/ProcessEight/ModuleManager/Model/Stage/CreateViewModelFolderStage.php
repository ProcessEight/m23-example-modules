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
use ProcessEight\ModuleManager\Model\ConfigKey;

/**
 * Class CreateViewModelFolderStage
 *
 * Creates a ViewModel/ folder
 *
 */
class CreateViewModelFolderStage extends BaseStage
{
    /**
     * @var string
     */
    public $id = 'createViewModelFolderStage';

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var \ProcessEight\ModuleManager\Service\Folder
     */
    private $folder;

    /**
     * CreateModuleFolder constructor.
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
     */
    public function configureStage(array $payload) : array
    {
//        // Ask the user for the METHOD_TO_INTERCEPT_NAMESPACE, if it was not passed in as an option
//        $payload['config'][$this->id]['options'][ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE] = [
//            'name'                    => ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE,
//            'shortcut'                => null,
//            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
//            'description'             => 'Method to intercept (in format \Vendor\Namespace\Path\To\Class::methodToIntercept)',
//            'question'                => '<question>Method to intercept (in format \Vendor\Namespace\Path\To\Class::methodToIntercept) []: </question> ',
//            'question_default_answer' => '',
//        ];
//        // Ask the user for the PLUGIN_TYPE, if it was not passed in as an option
//        $payload['config'][$this->id]['options'][ConfigKey::PLUGIN_TYPE] = [
//            'name'                    => ConfigKey::PLUGIN_TYPE,
//            'shortcut'                => null,
//            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
//            'description'             => 'Plugin type (before/after/around)',
//            'question'                => '<question>Plugin type (before/after/around) [before]: </question> ',
//            'question_default_answer' => 'before',
//        ];
        // Ask the user for the VIEW_MODEL_SUBDIRECTORY_PATH, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::VIEW_MODEL_SUBDIRECTORY_PATH] = [
            'name'                    => ConfigKey::VIEW_MODEL_SUBDIRECTORY_PATH,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'description'             => 'ViewModel subdirectory path, e.g. VENDOR_NAME/MODULE_NAME/ViewModel/Custom/Directory/Path/',
            'question'                => '<question>ViewModel subdirectory path, e.g. VENDOR_NAME/MODULE_NAME/ViewModel/Custom/Directory/Path/ []: </question> ',
            'question_default_answer' => '',
        ];
//        // Ask the user for the PLUGIN_CLASS_NAME, if it was not passed in as an option
//        $payload['config'][$this->id]['options'][ConfigKey::PLUGIN_CLASS_NAME] = [
//            'name'                    => ConfigKey::PLUGIN_CLASS_NAME,
//            'shortcut'                => null,
//            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
//            'description'             => 'Plugin class name, e.g. ClassNamePlugin',
//            'question'                => '<question>Plugin class name []: </question> ',
//            'question_default_answer' => '',
//        ];

        return $payload;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $subfolderPath        = 'ViewModel' . DIRECTORY_SEPARATOR . $payload['config'][$this->id]['values'][ConfigKey::VIEW_MODEL_SUBDIRECTORY_PATH];
        $absolutePathToFolder = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);

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

        $payload['messages'][] = "Created ViewModel folder at <info>{$absolutePathToFolder}</info>";

        // Pass payload onto next Stage/Pipeline
        return $payload;
    }
}
