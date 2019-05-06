<?php /** @noinspection PhpUndefinedClassInspection */
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

namespace ProcessEight\CustomLoggerExample\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomLoggerExample extends Command
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \ProcessEight\CustomLoggerExample\Logger\Logger
     */
    private $customLogger;

    /**
     * @var \Magento\Framework\Logger\Monolog
     */
    private $virtualTypeLogger;

    /**
     * CustomLoggerExample constructor.
     *
     * @param \Psr\Log\LoggerInterface                        $logger
     * @param \ProcessEight\CustomLoggerExample\Logger\Logger $customLogger
     * @param \Magento\Framework\Logger\Monolog               $virtualTypeLogger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \ProcessEight\CustomLoggerExample\Logger\Logger $customLogger,
        \Magento\Framework\Logger\Monolog $virtualTypeLogger
    ) {
        parent::__construct();

        $this->logger            = $logger;
        $this->customLogger      = $customLogger;
        $this->virtualTypeLogger = $virtualTypeLogger;
    }

    protected function configure()
    {
        $this->setName("process-eight:example:custom-logger");
        $this->setDescription("Demonstration of how to create a custom logger in Magento 2");
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Writing message with log level 'DEBUG' to var/log/debug.log using " . get_class($this->logger));
        $this->logger->debug("This is a message written by " . get_class($this->logger) . " at " . date('Ymd H:i:s'));
        $this->logger->info("This is a message written by " . get_class($this->logger) . " at " . date('Ymd H:i:s'));
        $this->logger->notice("This is a message written by " . get_class($this->logger) . " at " . date('Ymd H:i:s'));
        $this->logger->warning("This is a message written by " . get_class($this->logger) . " at " . date('Ymd H:i:s'));
        $this->logger->error("This is a message written by " . get_class($this->logger) . " at " . date('Ymd H:i:s'));
        $this->logger->critical("This is a message written by " . get_class($this->logger) . " at " . date('Ymd H:i:s'));
        $this->logger->alert("This is a message written by " . get_class($this->logger) . " at " . date('Ymd H:i:s'));
        $this->logger->emergency("This is a message written by " . get_class($this->logger) . " at " . date('Ymd H:i:s'));

        $output->writeln("Writing message with log level 'DEBUG' to var/log/custom_logger_example.log using " . get_class($this->customLogger));
        $this->customLogger->debug("This is a message written by " . get_class($this->customLogger) . " at " . date('Ymd H:i:s'));
        $this->customLogger->info("This is a message written by " . get_class($this->customLogger) . " at " . date('Ymd H:i:s'));
        $this->customLogger->notice("This is a message written by " . get_class($this->customLogger) . " at " . date('Ymd H:i:s'));
        $this->customLogger->warning("This is a message written by " . get_class($this->customLogger) . " at " . date('Ymd H:i:s'));
        $this->customLogger->error("This is a message written by " . get_class($this->customLogger) . " at " . date('Ymd H:i:s'));
        $this->customLogger->critical("This is a message written by " . get_class($this->customLogger) . " at " . date('Ymd H:i:s'));
        $this->customLogger->alert("This is a message written by " . get_class($this->customLogger) . " at " . date('Ymd H:i:s'));
        $this->customLogger->emergency("This is a message written by " . get_class($this->customLogger) . " at " . date('Ymd H:i:s'));

        $output->writeln("Writing message with log level 'DEBUG' to var/log/custom_logger_example_virtual_type.log using " . get_class($this->virtualTypeLogger));
        $this->virtualTypeLogger->debug("This is a message written by " . get_class($this->virtualTypeLogger) . " at " . date('Ymd H:i:s'));
        $this->virtualTypeLogger->info("This is a message written by " . get_class($this->virtualTypeLogger) . " at " . date('Ymd H:i:s'));
        $this->virtualTypeLogger->notice("This is a message written by " . get_class($this->virtualTypeLogger) . " at " . date('Ymd H:i:s'));
        $this->virtualTypeLogger->warning("This is a message written by " . get_class($this->virtualTypeLogger) . " at " . date('Ymd H:i:s'));
        $this->virtualTypeLogger->err("This is a message written by " . get_class($this->virtualTypeLogger) . " at " . date('Ymd H:i:s'));
        $this->virtualTypeLogger->critical("This is a message written by " . get_class($this->virtualTypeLogger) . " at " . date('Ymd H:i:s'));
        $this->virtualTypeLogger->alert("This is a message written by " . get_class($this->virtualTypeLogger) . " at " . date('Ymd H:i:s'));
        $this->virtualTypeLogger->emergency("This is a message written by " . get_class($this->virtualTypeLogger) . " at " . date('Ymd H:i:s'));

        $output->writeln("All done!");
    }
}
