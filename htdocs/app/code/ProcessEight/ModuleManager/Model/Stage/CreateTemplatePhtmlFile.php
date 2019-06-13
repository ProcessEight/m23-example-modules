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

/**
 * Creates a vendor-name/module-name/view/<area-code>/template/<template>.phtml file
 * Assumes that the vendor-name/module-name/view/<area-code>/template/ folder already exists
 */
class CreateTemplatePhtmlFile
{
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * Constructor
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
     */
    public function __invoke(array $config)
    {
        // Check if file exists
        $artefactFileName = $config['config']['create-phtml-file-stage']['file-path'] . DIRECTORY_SEPARATOR . $config['config']['create-phtml-file-stage']['file-name'];
        try {
            $isExists = $this->filesystemDriver->isExists($artefactFileName);
            if($isExists) {
                $config['messages'][] = "File already exists: <info>{$artefactFileName}</info>";

                return $config;
            }
        } catch (FileSystemException $e) {
            $config['is_valid']         = false;
            $config['messages'][] = "Failed checking folder exists: <info>{$artefactFileName}</info>: " . ($e->getMessage());

            return $config;
        }

        // Create file from template
        try {
            // Read template
            $artefactFileTemplate = $this->filesystemDriver->fileGetContents($config['config']['create-phtml-file-stage']['template-file-path']);
            $artefactFileTemplate = str_replace('{{VENDOR_NAME}}', $config['config']['create-phtml-file-stage']['template-variables']['{{VENDOR_NAME}}'],$artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{VENDOR_NAME_LOWERCASE}}',strtolower($config['config']['create-phtml-file-stage']['template-variables']['{{VENDOR_NAME}}']), $artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{MODULE_NAME}}', $config['config']['create-phtml-file-stage']['template-variables']['{{MODULE_NAME}}'],$artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{MODULE_NAME_LOWERCASE}}',strtolower($config['config']['create-phtml-file-stage']['template-variables']['{{MODULE_NAME}}']), $artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{TEMPLATE_NAME}}', $config['config']['create-phtml-file-stage']['template-variables']['{{TEMPLATE_NAME}}'], $artefactFileTemplate);
            $artefactFileTemplate = str_replace('{{YEAR}}', date('Y'), $artefactFileTemplate);

            // Write template to file
            $artefactFileResource = $this->filesystemDriver->fileOpen($artefactFileName, 'wb+');
            $this->filesystemDriver->fileWrite($artefactFileResource, $artefactFileTemplate);

        } catch (FileSystemException $e) {
            $config['is_valid']   = false;
            $config['messages'][] = "Failure: " . $e->getMessage();

            return $config;
        }
        $config['messages'][] = "Created template at <info>{$artefactFileName}</info>";

        return $config;
    }
}
