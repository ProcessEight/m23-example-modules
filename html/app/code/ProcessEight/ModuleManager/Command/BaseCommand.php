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

namespace ProcessEight\ModuleManager\Command;

use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Input\InputInterface;
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
     * @var array[]
     */
    public $pipelineConfig = [
        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        'is_valid' => true,
        // Stage options definition and values are added here by each stage
        'config'   => [],
        // 'configure' mode prepares each stage, 'process' mode executes logic of each stage
        'mode'     => '',
    ];

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                       $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        parent::__construct();
        /**
         * @todo Why did we rename masterPipeline to pipeline?
         */
        $this->masterPipeline = $pipeline;
        $this->directoryList  = $directoryList;
    }

    /**
     * Configures the current command.
     * Loop through all the stages in every pipeline and add their options to this command
     */
    protected function configure()
    {
        $options = array_column($this->pipelineConfig['config'], 'options');
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
     * Loop through all the options of all stages in every pipeline
     * and ask the user for values, if the value was not passed as an argument
     * Then set the values to the pipelineConfig
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Ask user for values which were not passed through as options
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $options = array_column($this->pipelineConfig['config'], 'options');
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
        foreach ($this->pipelineConfig['config'] as $stageClassName => $stageConfig) {
            foreach ($optionNames as $optionName => $optionValue) {
                $this->pipelineConfig['config'][$stageClassName]['values'][$optionName] = $input->getOption($optionName);
            }
        }

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
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP) .
               DIRECTORY_SEPARATOR . 'code' .
               DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::VENDOR_NAME) .
               DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::MODULE_NAME) .
               DIRECTORY_SEPARATOR . $trailingPath;
    }

    /**
     * Define all template variables used in all Stages/Pipelines used by this command
     *
     * @param string $stageId
     *
     * @return array
     */
    public function getTemplateVariables(string $stageId) : array
    {
        return [
            '{{VENDOR_NAME}}'           => $this->pipelineConfig['config'][$stageId]['values'][ConfigKey::VENDOR_NAME],
            '{{MODULE_NAME}}'           => $this->pipelineConfig['config'][$stageId]['values'][ConfigKey::MODULE_NAME],
            '{{VENDOR_NAME_LOWERCASE}}' => strtolower($this->pipelineConfig['config'][$stageId]['values'][ConfigKey::VENDOR_NAME]),
            '{{MODULE_NAME_LOWERCASE}}' => strtolower($this->pipelineConfig['config'][$stageId]['values'][ConfigKey::MODULE_NAME]),
            '{{YEAR}}'                  => date('Y'),
        ];
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
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP) .
               DIRECTORY_SEPARATOR . 'code' .
               DIRECTORY_SEPARATOR . 'ProcessEight' .
               DIRECTORY_SEPARATOR . 'ModuleManager' .
               DIRECTORY_SEPARATOR . 'Template' .
               DIRECTORY_SEPARATOR . $trailingPath .
               DIRECTORY_SEPARATOR . $fileName . '.template';
    }
}
