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

namespace ProcessEight\ModuleManager\Command\Module;

use ProcessEight\ModuleManager\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use ProcessEight\ModuleManager\Model\ConfigKey;

/**
 * Class BinMagentoCommandCommand
 *
 * Creates a new di.xml and PHP class
 *
 * @package ProcessEight\ModuleManager\Command\Module
 */
class BinMagentoCommandCommand extends BaseCommand
{
    /**
     * Used to generate file name
     */
    public $artefactFileName = '{{COMMAND_CLASS_NAME}}.php';

    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateBinMagentoCommandPipeline
     */
    private $createBinMagentoCommandPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                                  $masterPipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                            $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateBinMagentoCommandPipeline $createBinMagentoCommandPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $masterPipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateBinMagentoCommandPipeline $createBinMagentoCommandPipeline
    ) {
        parent::__construct($masterPipeline, $directoryList);
        $this->createBinMagentoCommandPipeline = $createBinMagentoCommandPipeline;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:command");
        $this->setDescription("Creates a new bin/magento command.");
        $this->addOption(
            ConfigKey::COMMAND_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Command name, e.g. process-eight:module:create'
        );
        $this->addOption(
            ConfigKey::COMMAND_DESCRIPTION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Brief description of the command'
        );
        $this->addOption(
            ConfigKey::COMMAND_CLASS_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Command class name'
        );
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
        parent::execute($input, $output);

        // Gather inputs
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(ConfigKey::COMMAND_NAME)) {
            $question = new Question('<question>Command name (e.g. process-eight:module:create): </question> ');

            $input->setOption(
                ConfigKey::COMMAND_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::COMMAND_DESCRIPTION)) {
            $question = new Question('<question>Command description: </question> ');

            $input->setOption(
                ConfigKey::COMMAND_DESCRIPTION,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::COMMAND_CLASS_NAME)) {
            $question = new Question('<question>Command class name: </question> ');

            $input->setOption(
                ConfigKey::COMMAND_CLASS_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        $result = $this->processPipeline($input);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;
    }

    /**
     * Prepare all the data needed to run all the stages/pipelines needed for this command, then execute the pipeline
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function processPipeline(\Symfony\Component\Console\Input\InputInterface $input) : array
    {
        // CreateFolderPipeline config
        $config['validate-vendor-name-stage'][ConfigKey::VENDOR_NAME] = $input->getOption(ConfigKey::VENDOR_NAME);
        $config['validate-module-name-stage'][ConfigKey::MODULE_NAME] = $input->getOption(ConfigKey::MODULE_NAME);
        $config['create-folder-stage']['folder-path']                 = $this->getAbsolutePathToFolder($input, 'Command');

        // Create PHP Class Stage config
        $config['create-php-class-file-stage']['file-path']          = $this->getAbsolutePathToFolder($input, 'Command');
        $config['create-php-class-file-stage']['file-name']          = $this->getProcessedFileName($input, '{{COMMAND_CLASS_NAME}}');
        $config['create-php-class-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-php-class-file-stage']['template-file-path'] = $this->getTemplateFilePath('{{COMMAND_CLASS_NAME}}.php', 'Command');

        // Create di.xml Stage config
        $config['create-xml-file-stage']['file-path']          = $this->getAbsolutePathToFolder($input, 'etc');
        $config['create-xml-file-stage']['file-name']          = 'di.xml';
        $config['create-xml-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-xml-file-stage']['template-file-path'] = $this->getTemplateFilePath('di.xml', 'etc');

        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        $masterPipelineConfig = [
            'is_valid' => true,
            'config'   => $config,
        ];

        // Run the pipeline
        return $this->createBinMagentoCommandPipeline->processPipeline($masterPipelineConfig);
    }

    /**
     * All template variables used in all pipelines used by this command
     *
     * @param InputInterface $input
     *
     * @return array
     */
    public function getTemplateVariables(\Symfony\Component\Console\Input\InputInterface $input) : array
    {
        $templateVariables = parent::getTemplateVariables($input);
        $templateVariables = array_merge($templateVariables, [
            '{{COMMAND_NAME}}'                 => $input->getOption(ConfigKey::COMMAND_NAME),
            '{{COMMAND_DESCRIPTION}}'          => $input->getOption(ConfigKey::COMMAND_DESCRIPTION),
            '{{COMMAND_CLASS_NAME}}'           => $input->getOption(ConfigKey::COMMAND_CLASS_NAME),
            // Change LOWERCASE to STRTOLOWER
            '{{COMMAND_CLASS_NAME_LOWERCASE}}' => strtolower($input->getOption(ConfigKey::COMMAND_CLASS_NAME)),
        ]);

        return $templateVariables;
    }
}
