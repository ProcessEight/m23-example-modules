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

namespace ProcessEight\ModuleManager\Model\Stage\Frontend;

use Magento\Framework\Exception\FileSystemException;
use ProcessEight\ModuleManager\Model\ConfigKey;
use ProcessEight\ModuleManager\Service\Folder;

/**
 * Class CreateFrontendControllerFolderStage
 *
 * Creates the module folder
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateFrontendControllerFolderStage extends \ProcessEight\ModuleManager\Model\Stage\BaseStage
{
    public $id = 'createFrontendControllerFolderStage';

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
        // Ask the user for the CONTROLLER_DIRECTORY_NAME, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::CONTROLLER_DIRECTORY_NAME] = [
            'name'                    => ConfigKey::CONTROLLER_DIRECTORY_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Controller directory name',
            'question'                => '<question>Controller directory name (the \'product\' in \'/catalog/product/edit\') [example]:</question> ',
            'question_default_answer' => 'example',
        ];

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
        $absolutePathToFolder = $this->folder->getAbsolutePathToFolder(
            $payload,
            $this->id,
            'Controller' . DIRECTORY_SEPARATOR . ucfirst($payload['config'][$this->id]['values'][ConfigKey::CONTROLLER_DIRECTORY_NAME])
        );

        // Check if folder exists
        try {
            $this->filesystemDriver->isExists($absolutePathToFolder);
        } catch (FileSystemException $e) {
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        // Create folder
        try {
            $this->filesystemDriver->createDirectory($absolutePathToFolder);
        } catch (FileSystemException $e) {
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        $payload['messages'][] = "Created controller folder at <info>{$absolutePathToFolder}</info>";

        // Pass payload onto next Stage/Pipeline
        return $payload;
    }

    /**
     * All template variables used by this stage
     *
     * @param string $stageId
     * @param array  $payload
     *
     * @return array
     */
    public function getTemplateVariables(string $stageId, array $payload) : array
    {
        return [
            '{{VENDOR_NAME}}'                       => $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}'                       => $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{VENDOR_NAME_LOWERCASE}}'             => strtolower($payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]),
            '{{MODULE_NAME_LOWERCASE}}'             => strtolower($payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME]),
            '{{YEAR}}'                              => date('Y'),
            /**
             * @todo These kind of Command-specific template variables should be moved out of here
             *       This stage is for creating a di.xml file
             *       Updating the di.xml file to include command-specific template variables should be added to a new 'UpdateDiXmlFileStage'
             */
            '{{FRONT_NAME}}'                        => $payload['config'][$stageId]['values'][ConfigKey::FRONT_NAME],
            '{{CONTROLLER_DIRECTORY_NAME}}'         => $payload['config'][$stageId]['values'][ConfigKey::CONTROLLER_DIRECTORY_NAME],
            '{{CONTROLLER_DIRECTORY_NAME_UCFIRST}}' => ucfirst($payload['config'][$stageId]['values'][ConfigKey::CONTROLLER_DIRECTORY_NAME]),
            '{{CONTROLLER_ACTION_NAME}}'            => $payload['config'][$stageId]['values'][ConfigKey::CONTROLLER_ACTION_NAME],
            '{{CONTROLLER_ACTION_NAME_UCFIRST}}'    => ucfirst($payload['config'][$stageId]['values'][ConfigKey::CONTROLLER_ACTION_NAME]),
        ];
    }
}
