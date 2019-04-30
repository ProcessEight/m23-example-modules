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

declare(strict_types=1);

namespace ProcessEight\CustomLoggerExample\Controller\Index;

/**
 * Serves /customloggerexample/index/index
 */
class Index extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \ProcessEight\CustomLoggerExample\Logger\Logger
     */
    private $logger;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context           $context
     * @param \Magento\Framework\View\Result\PageFactory      $resultPageFactory
     * @param \ProcessEight\CustomLoggerExample\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \ProcessEight\CustomLoggerExample\Logger\Logger $logger
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->logger            = $logger;
    }

    /**
     * A dummy controller, solely for demonstrating the custom logger
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $this->logger->debug('My debug log');
        $this->logger->info('My info log');
        $this->logger->notice('My notice log');
        $this->logger->warning('My warning log');
        $this->logger->error('My error log');
        $this->logger->critical('My critical log');
        $this->logger->alert('My alert log');
        $this->logger->emergency('My emergency log');

        return $this->resultPageFactory->create();
    }
}
