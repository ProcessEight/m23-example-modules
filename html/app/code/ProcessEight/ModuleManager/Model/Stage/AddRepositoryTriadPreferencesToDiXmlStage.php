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
 * Class AddRepositoryTriadPreferencesToDiXmlStage
 *
 */
class AddRepositoryTriadPreferencesToDiXmlStage extends BaseStage
{
    /**
     * @var string
     */
    public $id = 'AddRepositoryTriadPreferencesToDiXmlStage';

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
//        // Ask the user for the LAYOUT_XML_HANDLE, if it was not passed in as an option
//        $payload['config'][$this->id]['options'][ConfigKey::LAYOUT_XML_HANDLE] = [
//            'name'                    => ConfigKey::LAYOUT_XML_HANDLE,
//            'shortcut'                => null,
//            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
//            'description'             => 'Layout XML Handle',
//            'question'                => '<question>Layout XML handle (e.g. routerid_controllerfoldername_controllerclassname) (without .xml suffix): [default]</question> ',
//            'question_default_answer' => 'default',
//        ];
//        // Ask the user for the LAYOUT_XML_BLOCK_NAME, if it was not passed in as an option
//        $payload['config'][$this->id]['options'][ConfigKey::LAYOUT_XML_BLOCK_NAME] = [
//            'name'                    => ConfigKey::LAYOUT_XML_BLOCK_NAME,
//            'shortcut'                => null,
//            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
//            'description'             => 'Layout XML name of Block we\re adding View Model to',
//            'question'                => '<question>Layout XML name of Block we\'re adding View Model to (e.g. category.products.list): []</question> ',
//            'question_default_answer' => '',
//        ];
//        // Ask the user for the LAYOUT_XML_VIEW_MODEL_NAME, if it was not passed in as an option
//        $payload['config'][$this->id]['options'][ConfigKey::LAYOUT_XML_VIEW_MODEL_NAME] = [
//            'name'                    => ConfigKey::LAYOUT_XML_VIEW_MODEL_NAME,
//            'shortcut'                => null,
//            'mode'                    => \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED,
//            'description'             => 'View Model argument name in Layout XML',
//            'question'                => '<question>View Model argument name in Layout XML (e.g. custom_view_model): [view_model]</question> ',
//            'question_default_answer' => 'view_model',
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
        $subfolderPath    = 'etc' . DIRECTORY_SEPARATOR;
        $artefactFilePath = $this->folder->getAbsolutePathToFolder($payload, $this->id, $subfolderPath);
        $artefactFileName = 'di.xml';
        $blockName        = $payload['config'][$this->id]['values'][ConfigKey::LAYOUT_XML_BLOCK_NAME];
        $xpathExpression  = '//config';

        // Read XML file into DOM
        $dom = $this->xmlFileService->readXmlFileIntoDom($artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);

        // Find config node
        $node = $this->xmlFileService->getNodeByXpath($dom, $xpathExpression);
        if ($node !== null) {
            // Create preference node for Model Interface
            $preferenceNode = $dom->createElement('preference');
            $preferenceNode->setAttribute(
                'for',
                $this->getModelInterfaceClassName($payload)
            );
            $preferenceNode->setAttribute(
                'type',
//                $this->getModelInterfaceClassName($payload)
            );
            // Append Model Interface preference node to config node
            $node->appendChild($preferenceNode);

            // Create preference node for SearchResultsInterface
            $preferenceNode = $dom->createElement('preference');
            $preferenceNode->setAttribute(
                'for',
                $this->getSearchResultsInterfaceClassName($payload)
            );
            $preferenceNode->setAttribute(
                'type',
//                $this->getModelInterfaceClassName($payload)
            );
            // Append SearchResultsInterface preference node to config node
            $node->appendChild($preferenceNode);

            // Create preference node for RepositoryInterface
            $preferenceNode = $dom->createElement('preference');
            $preferenceNode->setAttribute(
                'for',
                $this->getRepositoryInterfaceClassName($payload)
            );
            $preferenceNode->setAttribute(
                'type',
//                $this->getModelInterfaceClassName($payload)
            );
            // Append RepositoryInterface preference node to config node
            $node->appendChild($preferenceNode);
        } else {
            $payload['messages'][] = "Failure: Could not find config node in {$artefactFileName}";
            $payload['is_valid']   = false;

            return $payload;
        }

        // Write the updated DOM tree to file
        $this->xmlFileService->writeDomToXmlFile($dom, $artefactFilePath . DIRECTORY_SEPARATOR . $artefactFileName);

        $payload['messages'][] = "{$artefactFileName} was successfully updated";

        // Pass payload onto next stage/pipeline
        return $payload;
    }

    /**
     * @param array $payload
     *
     * @return string
     */
    private function getModelInterfaceClassName(array $payload) : string
    {
        return implode('\\', [
            $payload['config'][$this->id]['values'][ConfigKey::VENDOR_NAME],
            $payload['config'][$this->id]['values'][ConfigKey::MODULE_NAME],
            'Api',
            'Data',
            ucfirst($payload['config'][$this->id]['values'][ConfigKey::ENTITY_NAME]) . 'Interface',
        ]);
    }

    /**
     * @param array $payload
     *
     * @return string
     */
    private function getRepositoryInterfaceClassName(array $payload) : string
    {
        return implode('\\', [
            $payload['config'][$this->id]['values'][ConfigKey::VENDOR_NAME],
            $payload['config'][$this->id]['values'][ConfigKey::MODULE_NAME],
            'Api',
            ucfirst($payload['config'][$this->id]['values'][ConfigKey::ENTITY_NAME]) . 'RepositoryInterface',
        ]);
    }

    /**
     * @param array $payload
     *
     * @return string
     */
    private function getSearchResultsInterfaceClassName(array $payload) : string
    {
        return implode('\\', [
            $payload['config'][$this->id]['values'][ConfigKey::VENDOR_NAME],
            $payload['config'][$this->id]['values'][ConfigKey::MODULE_NAME],
            'Api',
            'Data',
            ucfirst($payload['config'][$this->id]['values'][ConfigKey::ENTITY_NAME]) . 'SearchResultsInterface',
        ]);
    }
}
