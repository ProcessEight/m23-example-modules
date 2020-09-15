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
 * Class AddSetupVersionToModuleXmlStage
 *
 * Adds the setup_version attribute to a VENDOR_NAME/MODULE_NAME/etc/module.xml file
 * Assumes that the VENDOR_NAME/MODULE_NAME/etc/ folder already exists
 *
 */
class AddSetupVersionToModuleXmlStage extends BaseStage
{
    /**
     * @var string
     */
    public $id = 'addSetupVersionToModuleXmlStage';

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
     * @var \ProcessEight\ModuleManager\Service\Folder
     */
    private $folder;

    /**
     * @var \ProcessEight\ModuleManager\Service\Template
     */
    private $template;
    /**
     * @var \Magento\Framework\Filesystem\File\WriteFactory
     */
    private $readWriteFactory;
    /**
     * @var \Magento\Framework\DomDocument\DomDocumentFactory
     */
    private $domDocumentFactory;
    /**
     * @var \ProcessEight\ModuleManager\Service\XmlFileService
     */
    private $xmlFileService;

    /**
     * CreateModuleFolder constructor.
     *
     * @param \Magento\Framework\App\Filesystem\DirectoryList    $directoryList
     * @param \Magento\Framework\Filesystem\Driver\File          $filesystemDriver
     * @param \Magento\Framework\Module\Dir                      $moduleDir
     * @param \ProcessEight\ModuleManager\Service\Folder         $folder
     * @param \ProcessEight\ModuleManager\Service\Template       $template
     * @param \Magento\Framework\Filesystem\File\WriteFactory    $writeFactory
     * @param \Magento\Framework\DomDocument\DomDocumentFactory  $domDocumentFactory
     * @param \ProcessEight\ModuleManager\Service\XmlFileService $xmlFileService
     */
    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \Magento\Framework\Module\Dir $moduleDir,
        \ProcessEight\ModuleManager\Service\Folder $folder,
        \ProcessEight\ModuleManager\Service\Template $template,
        \Magento\Framework\Filesystem\File\WriteFactory $writeFactory,
        \Magento\Framework\DomDocument\DomDocumentFactory $domDocumentFactory,
        \ProcessEight\ModuleManager\Service\XmlFileService $xmlFileService
    ) {
        $this->directoryList      = $directoryList;
        $this->filesystemDriver   = $filesystemDriver;
        $this->moduleDir          = $moduleDir;
        $this->folder             = $folder;
        $this->template           = $template;
        $this->readWriteFactory   = $writeFactory;
        $this->domDocumentFactory = $domDocumentFactory;
        $this->xmlFileService     = $xmlFileService;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     * @throws FileSystemException
     */
    public function processStage(array $payload) : array
    {
        $subfolderPath    = 'etc';
        $artefactFilePath = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName = 'module.xml';

        // Read in module.xml file
        $dom = $this->xmlFileService->readXmlFileIntoDom($artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);

        // Get config node
        $configNode = $this->xmlFileService->getNodeByXpath($dom, '/config');

        // Get module node
        $moduleNode = $this->xmlFileService->getNodeByXpath($dom, '/config/module');

        // Add/Update the 'setup_version' attribute
        $moduleNode->setAttribute('setup_version', $payload['config'][$this->id]['values'][ConfigKey::SETUP_VERSION]);
        $configNode->appendChild($moduleNode);

        // Write the updated DOM tree to file
        $this->xmlFileService->writeDomToXmlFile($dom, $artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);

        $payload['messages'][] = "{$artefactFileName} was successfully updated with the new setup_version";

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}
