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

namespace ProcessEight\CreateModifyDeleteDirectoryExample\Command;

use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModifyDeleteDirectoryExample extends Command
{
    const MODULE_NAME = 'ProcessEight_CreateModifyDeleteDirectoryExample';

    const EXAMPLE_DIRECTORY_NAME = 'example_directory';
    const EXAMPLE_DIRECTORY_RENAME = 'example_directory_renamed';

    /**
     * Used to manage files and folders
     *
     * @var \Magento\Framework\Filesystem\DriverInterface
     */
    private $filesystemDriver;

    /**
     * Used to get the absolute path to the module
     *
     * @var \Magento\Framework\Component\ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * CreateModifyDeleteDirectoryExample constructor.
     *
     * @param \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     * @param \Magento\Framework\Filesystem\DriverInterface   $filesystemDriver
     */
    public function __construct(
        \Magento\Framework\Component\ComponentRegistrar $componentRegistrar,
        \Magento\Framework\Filesystem\DriverInterface $filesystemDriver
    ) {
        parent::__construct();

        $this->componentRegistrar = $componentRegistrar;
        $this->filesystemDriver   = $filesystemDriver;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName("process-eight:example:create-modify-delete-directory");
        $this->setDescription("Demonstrates how to programmatically create, modify and delete folders in Magento 2");
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
            ) . DIRECTORY_SEPARATOR . self::EXAMPLE_DIRECTORY_NAME;

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

        // Get renamed folder path
        $renamedFolderPath = $this->componentRegistrar->getPath(
                \Magento\Framework\Component\ComponentRegistrar::MODULE,
                self::MODULE_NAME
            ) . DIRECTORY_SEPARATOR . self::EXAMPLE_DIRECTORY_RENAME;

        // Rename folder
        try {
            $rename  = $this->filesystemDriver->rename($newFolderPath, $renamedFolderPath);
            $message = "Check if folder <info>{$newFolderPath}</info> was renamed: " . ($rename ? 'true' : 'false');
        } catch (FileSystemException $e) {
            $message = "Check if folder <info>{$newFolderPath}</info> was renamed: " . $e->getMessage();
        }
        $output->writeln($message);

        // Delete folder
        try {
            $delete  = $this->filesystemDriver->deleteDirectory($renamedFolderPath);
            $message = "Check if folder <info>{$renamedFolderPath}</info> was deleted: " . ($delete ? 'true' : 'false');
        } catch (FileSystemException $e) {
            $message = "Check if folder <info>{$renamedFolderPath}</info> was deleted: " . $e->getMessage();
        }
        $output->writeln($message);
    }
}
