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
 * Class CreateRoutesXmlFileStage
 *
 * Creates a vendor-name/module-name/etc/<area-code>/routes.xml file
 * Assumes that the vendor-name/module-name/etc/<area-code> folder already exists
 *
 * @package ProcessEight\ModuleManager\Model\Stage
 */
class CreateRoutesXmlFileStage extends BaseStage
{
    public $id = 'createRoutesXmlFile';

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * @var \Magento\Framework\Module\Dir
     */
    private $moduleDir;

    /**
     * CreateModuleFolder constructor.
     *
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Driver\File       $filesystemDriver
     * @param \Magento\Framework\Module\Dir                   $moduleDir
     */
    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \Magento\Framework\Module\Dir $moduleDir
    ) {
        $this->directoryList    = $directoryList;
        $this->filesystemDriver = $filesystemDriver;
        $this->moduleDir        = $moduleDir;
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $subfolderPath     = 'etc';
        $artefactFilePath  = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName  = 'di.xml';
        $templateFilePath  = $this->template->getTemplateFilePath($artefactFileName . '.template', $subfolderPath);
        $templateVariables = $this->getTemplateVariables($this->id, $payload);

        // Check if file exists
        try {
            $isExists = $this->filesystemDriver->isExists($artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);
            if ($isExists) {
                $payload['messages'][] = "<info>" . $artefactFileName . "</info> file already exists at <info>" . $artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName . "</info>";
                $payload['messages'][] = "<info>TODO: Add logic to modify existing files. For now, copy and paste the following into " . $artefactFileName . "</info>";
                $payload['messages'][] = "<info>" .
                                         $this->template->getProcessedTemplate($templateFilePath, $templateVariables) .
                                         "</info>";

                return $payload;
            }
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        // Create file from template
        try {
            // Read template
            $artefactFileTemplate = $this->template->getProcessedTemplate($templateFilePath, $templateVariables);

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen(
                $artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName,
                'wb+'
            );
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);
        } catch (FileSystemException $e) {
            $payload['is_valid']   = false;
            $payload['messages'][] = "Failure: " . $e->getMessage();

            return $payload;
        }

        $payload['messages'][] = "Created <info>" . $artefactFileName . "</info> file at <info>{$artefactFilePath}</info>";

        // Pass payload onto next stage/pipeline
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
            '{{VENDOR_NAME}}'                   => $payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}'                   => $payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{VENDOR_NAME_LOWERCASE}}'         => strtolower($payload['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]),
            '{{MODULE_NAME_LOWERCASE}}'         => strtolower($payload['config'][$stageId]['values'][ConfigKey::MODULE_NAME]),
            '{{YEAR}}'                          => date('Y'),
            /**
             * @todo These kind of Command-specific template variables should be moved out of here
             *       This stage is for creating a di.xml file
             *       Updating the di.xml file to include command-specific template variables should be added to a new 'UpdateDiXmlFileStage'
             */
            '{{COMMAND_NAME}}'                  => $payload['config'][$stageId]['values'][ConfigKey::COMMAND_NAME],
            '{{COMMAND_DESCRIPTION}}'           => $payload['config'][$stageId]['values'][ConfigKey::COMMAND_DESCRIPTION],
            '{{COMMAND_CLASS_NAME}}'            => $payload['config'][$stageId]['values'][ConfigKey::COMMAND_CLASS_NAME],
            '{{COMMAND_CLASS_NAME_UCFIRST}}'    => ucfirst($payload['config'][$stageId]['values'][ConfigKey::COMMAND_CLASS_NAME]),
            '{{COMMAND_CLASS_NAME_STRTOLOWER}}' => strtolower($payload['config'][$stageId]['values'][ConfigKey::COMMAND_CLASS_NAME]),
        ];
    }

//    /**
//     * @param mixed[] $config
//     *
//     * @return mixed[]
//     */
//    public function __invoke(array $config)
//    {
//        // Get absolute path to module etc folder
//
//        // Create file from template
//        try {
//            // Read template
//            $artefactFileTemplate = $this->filesystemDriver->fileGetContents(implode(DIRECTORY_SEPARATOR, [
//                    $this->moduleDir->getDir('ProcessEight_ModuleManager'),
//                    'Template',
//                    'etc',
//                    $config['data']['area-code'],
//                    self::ARTEFACT_FILE_NAME . '.template',
//                ]
//            ));
//            $artefactFileTemplate = str_replace('{{VENDOR_NAME}}', $config['data'][ConfigKey::VENDOR_NAME],
//                $artefactFileTemplate);
//            $artefactFileTemplate = str_replace('{{VENDOR_NAME_LOWERCASE}}',
//                strtolower($config['data'][ConfigKey::VENDOR_NAME]), $artefactFileTemplate);
//            $artefactFileTemplate = str_replace('{{MODULE_NAME}}', $config['data'][ConfigKey::MODULE_NAME],
//                $artefactFileTemplate);
//            $artefactFileTemplate = str_replace('{{MODULE_NAME_LOWERCASE}}',
//                strtolower($config['data'][ConfigKey::MODULE_NAME]), $artefactFileTemplate);
//            $artefactFileTemplate = str_replace('{{FRONT_NAME}}', $config['data'][ConfigKey::FRONT_NAME],
//                $artefactFileTemplate);
//            $artefactFileTemplate = str_replace('{{YEAR}}', date('Y'), $artefactFileTemplate);
//
//            // Write template to file
//            $artefactFileResource = $this->filesystemDriver->fileOpen($artefactFilePath, 'wb+');
//            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);
//
//        } catch (FileSystemException $e) {
//            $config['is_valid']   = false;
//            $config['messages'][] = "Failure: " . $e->getMessage();
//
//            return $config;
//        }
//        $config['messages'][] = "Created <info>" . self::ARTEFACT_FILE_NAME . "</info> file at <info>{$artefactFilePath}</info>";
//
//        return $config;
//    }
}