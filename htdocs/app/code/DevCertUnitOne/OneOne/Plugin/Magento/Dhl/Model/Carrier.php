<?php
/**
 * Process Eight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 Process Eight
 * @author      Process Eight
 *
 */

namespace DevCertUnitOne\OneOne\Plugin\Magento\Dhl\Model;

class Carrier
{
    const LOG_PATH = 'var';

    /**
     * @var \Magento\Framework\Module\Dir
     */
    private $moduleDir;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteFactory
     */
    private $writeFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Carrier constructor.
     *
     * @param \Magento\Framework\Module\Dir                        $moduleDir
     * @param \Magento\Framework\Filesystem\Directory\WriteFactory $writeFactory
     * @param \Psr\Log\LoggerInterface                             $logger
     */
    public function __construct(
        \Magento\Framework\Module\Dir $moduleDir,
        \Magento\Framework\Filesystem\Directory\WriteFactory $writeFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->moduleDir    = $moduleDir;
        $this->writeFactory = $writeFactory;
        $this->logger       = $logger;
    }

    /**
     * Log the parameters
     *
     * @param \Magento\Dhl\Model\Carrier    $subject The targeted class
     * @param \Magento\Framework\DataObject $request Parameter(s) of the targeted method
     *
     * @return null
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    public function beforeSetRequest(
        \Magento\Dhl\Model\Carrier $subject,    // The target class
        \Magento\Framework\DataObject $request  // Parameter(s) of the targeted method
    )
    {
        $this->createLogFolder();

        $this->logger->debug(__METHOD__ . '::abc123');

        // If the method does not change the argument for the observed method, it should return null.
        return null;
    }

    /**
     * Log the parameters
     *
     * @param \Magento\Dhl\Model\Carrier    $subject The targeted class
     * @param \Magento\Dhl\Model\Carrier    $result  Result of the targeted method
     * @param \Magento\Framework\DataObject $request Parameter(s) of the targeted method
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    public function afterSetRequest(
        \Magento\Dhl\Model\Carrier $subject,    // The target class
        $result,                                // Result of the targeted method
        \Magento\Framework\DataObject $request  // Parameter(s) of the targeted method
    )
    {
        $this->createLogFolder();

        $this->logger->debug(__METHOD__ . '::def456');

        return $result;
    }

    /**
     * Create log folder if it does not already exist
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    private function createLogFolder()
    {
        // Get path to module
        $modulePath = $this->moduleDir->getDir('DevCertUnitOne_OneOne');

        $writer = $this->writeFactory->create($modulePath);
        if (!$writer->isDirectory($modulePath . DIRECTORY_SEPARATOR . self::LOG_PATH)) {
            $writer->create(self::LOG_PATH);
        }
    }
}
