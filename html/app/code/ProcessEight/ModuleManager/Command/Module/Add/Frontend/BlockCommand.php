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
 * Class BlockCommand
 *
 * Creates a block class file
 *
 * @todo Add logic to create the block layout XML instruction and output to terminal
 * @todo Add logic to check if the target layout XML file exists
 * @todo Add logic to create the target layout XML file if not
 * @todo Add logic to programmatically add the block to the target layout XML file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Frontend
 */
class BlockCommand extends BaseCommand
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateBlockCommandPipeline
     */
    private $createBlockCommandPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                             $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                       $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateBlockCommandPipeline $createBlockCommandPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateBlockCommandPipeline $createBlockCommandPipeline
    ) {
        parent::__construct($pipeline, $directoryList);
        $this->createBlockCommandPipeline = $createBlockCommandPipeline;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:frontend:block");
        $this->setDescription("Adds a new PHP Block class.");
        $this->pipelineConfig['mode'] = 'configure';

        $this->pipelineConfig = $this->createBinMagentoCommandPipeline->processPipeline($this->pipelineConfig);

//        $this->addOption(ConfigKey::BLOCK_CLASS_NAME, null, InputOption::VALUE_OPTIONAL, 'Block class name');
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

        $result = $this->createBinMagentoCommandPipeline->processPipeline($this->pipelineConfig);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;

        // Gather inputs
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(ConfigKey::BLOCK_CLASS_NAME)) {
            $question = new Question('<question>Block class name: [Content]</question>', 'Content');

            $input->setOption(
                ConfigKey::BLOCK_CLASS_NAME,
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
        // ValidateModuleNamePipeline config
        $config['validate-vendor-name-stage'][ConfigKey::VENDOR_NAME] = $input->getOption(ConfigKey::VENDOR_NAME);
        $config['validate-module-name-stage'][ConfigKey::MODULE_NAME] = $input->getOption(ConfigKey::MODULE_NAME);

        // CreateFolderPipeline config
        $config['validate-vendor-name-stage'][ConfigKey::VENDOR_NAME] = $input->getOption(ConfigKey::VENDOR_NAME);
        $config['validate-module-name-stage'][ConfigKey::MODULE_NAME] = $input->getOption(ConfigKey::MODULE_NAME);
        $config['create-folder-stage']['folder-path']                 = $this->getAbsolutePathToFolder($input, 'Block');

        // Create PHP Class Stage config
        $config['create-php-class-file-stage']['file-path']          = $config['create-folder-stage']['folder-path'];
        $config['create-php-class-file-stage']['file-name']          = ucwords(str_replace('{{BLOCK_CLASS_NAME}}', $input->getOption(ConfigKey::BLOCK_CLASS_NAME), '{{BLOCK_CLASS_NAME}}.php'));
        $config['create-php-class-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-php-class-file-stage']['template-file-path'] = $this->getTemplateFilePath('{{BLOCK_CLASS_NAME}}.php', 'Block');

        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        $masterPipelineConfig = [
            'is_valid' => true,
            'config'   => $config,
        ];

        // Run the pipeline
        return $this->createBlockCommandPipeline->processPipeline($masterPipelineConfig);
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
            '{{BLOCK_CLASS_NAME}}' => $input->getOption(ConfigKey::BLOCK_CLASS_NAME),
        ]);

        return $templateVariables;
    }
}
