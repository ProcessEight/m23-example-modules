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
 * @copyright   Copyright (c) 2019 ProcessEight
 * @author      ProcessEight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Command\Module\Add\Frontend;

use ProcessEight\ModuleManager\Command\BaseCommand;
use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class TemplateCommand
 *
 * Creates a view/frontend/template/{{template-name}}.phtml file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Frontend
 */
class TemplateCommand extends BaseCommand
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateTemplateCommandPipeline
     */
    private $createTemplateCommandPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                                $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                          $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateTemplateCommandPipeline $createTemplateCommandPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateTemplateCommandPipeline $createTemplateCommandPipeline
    ) {
        parent::__construct($pipeline, $directoryList);
        $this->createTemplateCommandPipeline = $createTemplateCommandPipeline;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:frontend:template");
        $this->setDescription("Adds a new template PHTML file to the frontend area.");
        $this->pipelineConfig['mode'] = 'configure';

        $this->pipelineConfig = $this->createTemplateCommandPipeline->processPipeline($this->pipelineConfig);

//        $this->addOption(ConfigKey::TEMPLATE_NAME, null, InputOption::VALUE_OPTIONAL, 'Template PHTML name');
        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $this->pipelineConfig['mode'] = 'process';

        $result = $this->createTemplateCommandPipeline->processPipeline($this->pipelineConfig);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;

        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(ConfigKey::TEMPLATE_NAME)) {
            $question = new Question('<question>Template PHTML name: [content]</question> ', 'content');

            $input->setOption(
                ConfigKey::TEMPLATE_NAME,
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
        $config['create-folder-stage']['folder-path'] = $this->getAbsolutePathToFolder($input, 'view' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'templates');

        // Create PHTML File Stage config
        $config['create-phtml-file-stage']['file-path']          = $config['create-folder-stage']['folder-path'];
        $config['create-phtml-file-stage']['file-name']          = strtolower(str_replace('{{TEMPLATE_NAME}}', $input->getOption(ConfigKey::TEMPLATE_NAME), '{{TEMPLATE_NAME}}.phtml'));
        $config['create-phtml-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-phtml-file-stage']['template-file-path'] = $this->getTemplateFilePath('{{TEMPLATE_NAME}}.phtml', 'view' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'templates');

        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        $masterPipelineConfig = [
            'is_valid' => true,
            'config'   => $config,
        ];

        // Run the pipeline
        return $this->createTemplateCommandPipeline->processPipeline($masterPipelineConfig);
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
            '{{TEMPLATE_NAME}}' => $input->getOption(ConfigKey::TEMPLATE_NAME),
        ]);

        return $templateVariables;
    }
}
