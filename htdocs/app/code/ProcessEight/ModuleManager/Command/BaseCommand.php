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

namespace ProcessEight\ModuleManager\Command;

use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class BaseCommand
 *
 * Contains common logic for creating Pipeline commands
 *
 * @package ProcessEight\ModuleManager\Command
 */
class BaseCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * Used to generate file name
     */
    public $artefactFileName;

    /**
     * Area code this command is working with
     */
    public $areaCode = 'frontend';

    /**
     * @var \League\Pipeline\Pipeline
     */
    public $masterPipeline;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                       $masterPipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \League\Pipeline\Pipeline $masterPipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        parent::__construct();
        $this->masterPipeline = $masterPipeline;
        $this->directoryList  = $directoryList;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->addOption(ConfigKey::VENDOR_NAME, null, InputOption::VALUE_OPTIONAL, 'Vendor name');
        $this->addOption(ConfigKey::MODULE_NAME, null, InputOption::VALUE_OPTIONAL, 'Module name');
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
     * @return int|null null or 0 if everything went fine, or an error code
     *
     * @see setCode()
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(ConfigKey::VENDOR_NAME)) {
            $question = new Question('<question>Vendor name [ProcessEight]: </question> ', 'ProcessEight');

            $input->setOption(
                ConfigKey::VENDOR_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::MODULE_NAME)) {
            $question = new Question('<question>Module name [Test]: </question> ', 'Test');

            $input->setOption(
                ConfigKey::MODULE_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        // Example of how to call, and handle the results from, the pipeline
//        $result = $this->processPipeline($input);
//
//        foreach ($result['messages'] as $message) {
//            $output->writeln($message);
//        }
//
//        return $result['is_valid'] ? 0 : 1;

        return null;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string                                          $trailingPath
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getAbsolutePathToFolder(
        \Symfony\Component\Console\Input\InputInterface $input,
        string $trailingPath = ''
    ) : string {
        $absolutePathToFolder = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP) .
                                DIRECTORY_SEPARATOR . 'code' .
                                DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::VENDOR_NAME) .
                                DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::MODULE_NAME) .
                                DIRECTORY_SEPARATOR . $trailingPath;

        return $absolutePathToFolder;
    }

    /**
     * Replace template variables in file name
     *
     * @param InputInterface $input
     * @param                $replace
     *
     * @return string
     */
    public function getProcessedFileName(\Symfony\Component\Console\Input\InputInterface $input, $replace) : string
    {
        $artefactFileName = str_replace(
            $replace,
            $input->getOption(ConfigKey::COMMAND_CLASS_NAME),
            $this->artefactFileName
        );

        return $artefactFileName;
    }

    /**
     * All template variables used in all Stages/Pipelines used by this command
     *
     * @param InputInterface $input
     *
     * @return array
     */
    public function getTemplateVariables(\Symfony\Component\Console\Input\InputInterface $input) : array
    {
        $templateVariables = [
            '{{VENDOR_NAME}}'           => $input->getOption(ConfigKey::VENDOR_NAME),
            '{{MODULE_NAME}}'           => $input->getOption(ConfigKey::MODULE_NAME),
            '{{VENDOR_NAME_LOWERCASE}}' => strtolower($input->getOption(ConfigKey::VENDOR_NAME)),
            '{{MODULE_NAME_LOWERCASE}}' => strtolower($input->getOption(ConfigKey::MODULE_NAME)),
            '{{YEAR}}'                  => date('Y'),
        ];

        return $templateVariables;
    }

    /**
     * Return path to the template file
     *
     * @param string $fileName     File name has '.template' appended
     * @param string $trailingPath Sub-folder within Template folder (if any) which contains the template file
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getTemplateFilePath(string $fileName, string $trailingPath = '') : string
    {
        $templateFilePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP) .
                            DIRECTORY_SEPARATOR . 'code' .
                            DIRECTORY_SEPARATOR . 'ProcessEight' .
                            DIRECTORY_SEPARATOR . 'ModuleManager' .
                            DIRECTORY_SEPARATOR . 'Template' .
                            DIRECTORY_SEPARATOR . $trailingPath .
                            DIRECTORY_SEPARATOR . $fileName . '.template';

        return $templateFilePath;
    }
}
