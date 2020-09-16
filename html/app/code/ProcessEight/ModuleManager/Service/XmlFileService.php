<?php

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Service;

use Magento\Framework\Exception\FileSystemException;

class XmlFileService
{
    /**
     * @var \Magento\Framework\Filesystem\File\WriteFactory
     */
    private $readWriteFactory;
    /**
     * @var \Magento\Framework\DomDocument\DomDocumentFactory
     */
    private $domDocumentFactory;

    /**
     * XmlFileService constructor.
     *
     * @param \Magento\Framework\Filesystem\File\WriteFactory   $readWriteFactory
     * @param \Magento\Framework\DomDocument\DomDocumentFactory $domDocumentFactory
     */
    public function __construct(
        \Magento\Framework\Filesystem\File\WriteFactory $readWriteFactory,
        \Magento\Framework\DomDocument\DomDocumentFactory $domDocumentFactory
    ) {
        $this->readWriteFactory   = $readWriteFactory;
        $this->domDocumentFactory = $domDocumentFactory;
    }

    /**
     * Read XML file and convert into a DOMDocument object
     *
     * @param string $path
     *
     * @return \DOMDocument
     */
    public function readXmlFileIntoDom(string $path) : \DOMDocument
    {
        // Read in module.xml file
        $readModuleXml = $this->readWriteFactory->create(
            $path,
            \Magento\Framework\Filesystem\DriverPool::FILE,
            'r'
        );
        $dom           = $this->domDocumentFactory->create();
        $fileContent   = $readModuleXml->readAll();
        $dom->loadXML($fileContent);

        return $dom;
    }

    /**
     * @param \DOMDocument $dom
     * @param string       $filePath
     *
     * @throws FileSystemException
     */
    public function writeDomToXmlFile(\DOMDocument $dom, string $filePath)
    {
        $dom->formatOutput = true;
        $writeModuleXml    = $this->readWriteFactory->create(
            $filePath,
            \Magento\Framework\Filesystem\DriverPool::FILE,
            'w'
        );
        $writeModuleXml->write($dom->saveXML());
        $writeModuleXml->close();
    }

    /**
     * @param \DOMDocument  $domDocument
     * @param string        $xpathExpression
     * @param \DOMNode|null $contextNode
     *
     * @return \DOMNode|null
     */
    public function getNodeByXpath(
        \DOMDocument $domDocument,
        string $xpathExpression,
        \DOMNode $contextNode = null
    ) : ?\DOMNode {
        $xpath    = new \DOMXPath($domDocument);
        $nodeList = $xpath->query($xpathExpression, $contextNode);

        return $nodeList->item(0);
    }
}
