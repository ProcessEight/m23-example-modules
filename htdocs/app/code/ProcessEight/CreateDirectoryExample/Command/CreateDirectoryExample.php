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

namespace ProcessEight\CreateDirectoryExample\Command;

use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDirectoryExample extends Command
{
    const MODULE_NAME = 'ProcessEight_CreateDirectoryExample';

    const DIRECTORY_NAME = 'example_directory';

    /**
     * Used to manage files and folders
     *
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystemDriver;

    /**
     * Used to get the absolute path to the module
     *
     * @var \Magento\Framework\Component\ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * CreateDirectoryExample constructor.
     *
     * @param \Magento\Framework\Filesystem\Driver\File       $filesystemDriver
     * @param \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     */
    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystemDriver,
        \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
    ) {
        parent::__construct();

        $this->filesystemDriver   = $filesystemDriver;
        $this->componentRegistrar = $componentRegistrar;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName("process-eight:create-directory-example");
        $this->setDescription("Demonstrates how to programmatically create folders in Magento 2");
        parent::configure();
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void null or 0 if everything went fine, or an error code
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get new folder path
        $newFolderPath = $this->componentRegistrar->getPath(
                \Magento\Framework\Component\ComponentRegistrar::MODULE,
                self::MODULE_NAME
            ) . DIRECTORY_SEPARATOR . self::DIRECTORY_NAME;

        // Check if folder exists
        try {
            $isExists = $this->filesystemDriver->isExists($newFolderPath);
            $message  = "Check if folder exists at <info>{$newFolderPath}</info>: " . ($isExists ? 'true' : 'false');
        } catch (FileSystemException $e) {
            $message = "Check if folder exists at <info>{$newFolderPath}</info>: " . ($e->getMessage());
        }
        $output->writeln($message);

        // Alternate way of checking for existence of folder
        try {
            $isDirectory = $this->filesystemDriver->isDirectory($newFolderPath);
            $message     = "Alternate way of checking for existence of folder at <info>{$newFolderPath}</info>: " . ($isDirectory ? 'true' : 'false');
        } catch (FileSystemException $e) {
            $message = "Alternate way of checking for existence of folder at <info>{$newFolderPath}</info>: " . $e->getMessage();
        }
        $output->writeln($message);

        // Create folder
        try {
            $createDirectory = $this->filesystemDriver->createDirectory($newFolderPath);
            $message         = "Folder was successfully created at <info>'{$newFolderPath}'</info> with default permissions of '<info>0777</info>': " . ($createDirectory ? 'true' : 'false');
        } catch (FileSystemException $e) {
            $message = "Failed to create folder at <info>'{$newFolderPath}'</info> with default permissions of '<info>0777</info>'" . $e->getMessage();
        }
        $output->writeln($message);

        // Check if folder exists
        try {
            $isExists = $this->filesystemDriver->isExists($newFolderPath);
            $message  = "Check if folder exists at <info>{$newFolderPath}</info>: " . ($isExists ? 'true' : 'false');
        } catch (FileSystemException $e) {
            $message = "Check if folder exists at <info>{$newFolderPath}</info>: " . $e->getMessage();
        }
        $output->writeln($message);

        // Alternate way of checking for existence of folder
        try {
            $isDirectory = $this->filesystemDriver->isDirectory($newFolderPath);
            $message     = "Alternate way of checking for existence of folder at <info>{$newFolderPath}</info>: " . ($isDirectory ? 'true' : 'false');
        } catch (FileSystemException $e) {
            $message = "Alternate way of checking for existence of folder at <info>{$newFolderPath}</info>: " . $e->getMessage();
        }
        $output->writeln($message);

        // Check if folder can be written to
        try {
            $isWritable = $this->filesystemDriver->isWritable($newFolderPath);
            $message    = "Check if folder <info>{$newFolderPath}</info> is writable: " . ($isWritable ? 'true' : 'false');
        } catch (FileSystemException $e) {
            $message = "Check if folder <info>{$newFolderPath}</info> is writable: " . $e->getMessage();
        }
        $output->writeln($message);
    }
}
