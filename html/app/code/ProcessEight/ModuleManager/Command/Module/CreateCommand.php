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

namespace ProcessEight\ModuleManager\Command\Module;

use ProcessEight\ModuleManager\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Question\Question;

class CreateCommand extends BaseCommand
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateModulePipeline
     */
    private $createModulePipeline;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var array[]
     */
    private $stagesConfig;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                       $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                 $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateModulePipeline $createModulePipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateModulePipeline $createModulePipeline
    ) {
        $this->createModulePipeline = $createModulePipeline;
        $this->directoryList        = $directoryList;
        parent::__construct($pipeline, $directoryList);
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->stagesConfig = [
            // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
            'is_valid' => true,
            // Stage options definition and values are added here by each stage
            'config'   => [],
            // 'configure' mode prepares each stage, 'process' mode executes logic of each stage
            'mode'     => '',
        ];

        $this->setName("process-eight:module:create");
        $this->setDescription("Creates a new module with etc/module.xml, registration.php and composer.json files.");

        $this->stagesConfig = $this->configurePipeline();

        $options = array_column($this->stagesConfig['config'], 'options');
        foreach ($options as $stageOptions) {
            foreach ($stageOptions as $optionConfig) {
                $this->addOption(
                    $optionConfig['name'],
                    $optionConfig['shortcut'],
                    $optionConfig['mode'],
                    $optionConfig['description']
                );
            }
        }
    }

    /**
     * Execute the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Ask user for values which were not passed through as options
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $options = array_column($this->stagesConfig['config'], 'options');
        foreach ($options as $stageOptions) {
            foreach ($stageOptions as $optionConfig) {
                if (!$input->getOption($optionConfig['name'])) {
                    $question = new Question($optionConfig['question'], $optionConfig['question_default_answer']);

                    $input->setOption($optionConfig['name'], $questionHelper->ask($input, $output, $question));
                }
            }
        }

        // Loop through and populate 'values' array
        // This step also makes all option values available to all stages
        $optionNames = [];
        foreach ($options as $option) {
            $optionNames = array_merge($optionNames, array_keys($option));
        }
        $optionNames = array_flip($optionNames);
        foreach ($this->stagesConfig['config'] as $stageClassName => $stageConfig) {
            foreach ($optionNames as $optionName => $optionValue) {
                $this->stagesConfig['config'][$stageClassName]['values'][$optionName] = $input->getOption($optionName);
            }
        }

        $result = $this->processPipeline($input);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;
    }

    /**
     * This method gathers all the options defined by each stage
     *
     * @return array
     */
    public function configurePipeline() : array
    {
        $this->stagesConfig['mode'] = 'configure';

        return $this->createModulePipeline->processPipeline($this->stagesConfig);
    }

    /**
     * Execute the logic in each stage
     *
     * @return array
     */
    private function processPipeline() : array
    {
        $this->stagesConfig['mode'] = 'process';

        return $this->createModulePipeline->processPipeline($this->stagesConfig);
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
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP)
               . DIRECTORY_SEPARATOR . 'code'
               . DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::VENDOR_NAME)
               . DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::MODULE_NAME)
               . DIRECTORY_SEPARATOR . $trailingPath;
    }
}
