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
 * Class AddViewModelToFrontendLayoutXmlStage
 *
 */
class AddViewModelToFrontendLayoutXmlStage extends BaseStage
{
    /**
     * @var string
     */
    public $id = 'AddViewModelToFrontendLayoutXmlStage';

    /**
     * @var \ProcessEight\ModuleManager\Service\Folder
     */
    private $folder;

    /**
     * @var \ProcessEight\ModuleManager\Service\XmlFileService
     */
    private $xmlFileService;

    /**
     * CreateModuleFolder constructor.
     *
     * @param \ProcessEight\ModuleManager\Service\Folder         $folder
     * @param \ProcessEight\ModuleManager\Service\XmlFileService $xmlFileService
     */
    public function __construct(
        \ProcessEight\ModuleManager\Service\Folder $folder,
        \ProcessEight\ModuleManager\Service\XmlFileService $xmlFileService
    ) {
        $this->folder         = $folder;
        $this->xmlFileService = $xmlFileService;
    }

    /**
     * @param mixed[] $payload
     *
     * @return mixed[]
     */
    public function configureStage(array $payload) : array
    {
        // Ask the user for the LAYOUT_XML_HANDLE, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::LAYOUT_XML_HANDLE] = [
            'name'                    => ConfigKey::LAYOUT_XML_HANDLE,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Layout XML Handle',
            'question'                => '<question>Layout XML handle (e.g. routerid_controllerfoldername_controllerclassname) (without .xml suffix): [default]</question> ',
            'question_default_answer' => 'default',
        ];
        // Ask the user for the VIEW_MODEL_BLOCK_CLASS_NAME, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::VIEW_MODEL_BLOCK_CLASS_NAME] = [
            'name'                    => ConfigKey::VIEW_MODEL_BLOCK_CLASS_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Class name of Block we\'re adding View Model to',
            'question'                => '<question>Class name of Block we\'re adding View Model to (e.g. VENDOR_NAME\MODULE_NAME\Block\Custom): []</question> ',
            'question_default_answer' => '',
        ];
        // Ask the user for the LAYOUT_XML_BLOCK_NAME, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::LAYOUT_XML_BLOCK_NAME] = [
            'name'                    => ConfigKey::LAYOUT_XML_BLOCK_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'Layout XML name of Block we\re adding View Model to',
            'question'                => '<question>Layout XML name of Block we\'re adding View Model to (e.g. category.products.list): []</question> ',
            'question_default_answer' => '',
        ];
        // Ask the user for the LAYOUT_XML_VIEW_MODEL_NAME, if it was not passed in as an option
        $payload['config'][$this->id]['options'][ConfigKey::LAYOUT_XML_VIEW_MODEL_NAME] = [
            'name'                    => ConfigKey::LAYOUT_XML_VIEW_MODEL_NAME,
            'shortcut'                => null,
            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
            'description'             => 'View Model argument name in Layout XML',
            'question'                => '<question>Layout XML name of Block we\'re adding View Model to (e.g. category.products.list): []</question> ',
            'question_default_answer' => '',
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
        $subfolderPath    = 'view' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'layout';
        $artefactFilePath = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName = $payload['config'][$this->id]['values'][ConfigKey::LAYOUT_XML_HANDLE] . '.xml';
        $blockName        = $payload['config'][$this->id]['values'][ConfigKey::LAYOUT_XML_BLOCK_NAME];
        $xpathExpression  = '//referenceBlock[@name="' . $blockName . '"]|//block[@name="' . $blockName . '"]';

        // Read XML file into DOM
        $dom = $this->xmlFileService->readXmlFileIntoDom($artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);

        // Find block/referenceBlock nodes
        $blockNode = $this->xmlFileService->getNodeByXpath($dom, $xpathExpression);
        if ($blockNode !== null) {
            // Test for existence of arguments node; Create one if necessary
            $argumentsNode = $this->xmlFileService->getNodeByXpath($dom, './arguments', $blockNode);
            if ($argumentsNode == null) {
                // Create arguments node
                $argumentsNode = $dom->createElement('arguments');
                // Append arguments node to block node
                $blockNode->appendChild($argumentsNode);
            }
            // Create argument node
            $argumentNode = $dom->createElement('argument');
            $argumentNode->setAttribute(
                'name',
                $payload['config'][$this->id]['values'][ConfigKey::LAYOUT_XML_VIEW_MODEL_NAME]
            );
            $argumentNode->setAttribute('xsi:type', 'object');
            $argumentNode->appendChild(new \DOMText($payload['config'][$this->id]['values'][ConfigKey::VIEW_MODEL_BLOCK_CLASS_NAME]));
            // Append argument node to arguments node
            $argumentsNode->appendChild($argumentNode);
        } else {
            $payload['messages'][] = "Failure: Could not find block {$blockName} in {$artefactFileName}";
            $payload['is_valid']   = false;

            return $payload;
        }

        // Write the updated DOM tree to file
        $this->xmlFileService->writeDomToXmlFile($dom, $artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);

        $payload['messages'][] = "{$artefactFileName} was successfully updated";

        // Pass payload onto next stage/pipeline
        return $payload;
    }
}
