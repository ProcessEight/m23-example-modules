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

namespace ProcessEight\ModuleManager\Command\Module\Add\Frontend;

use ProcessEight\ModuleManager\Command\BaseCommand;
use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class LayoutCommand
 *
 * Creates a view/<area-code>/layout/<layout-xml-handle>.xml file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Frontend
 */
class LayoutCommand extends BaseCommand
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateXmlFilePipeline
     */
    private $createXmlFilePipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                        $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                  $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateXmlFilePipeline $createXmlFilePipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateXmlFilePipeline $createXmlFilePipeline
    ) {
        parent::__construct($pipeline, $directoryList);
        $this->pipeline              = $pipeline;
        $this->createXmlFilePipeline = $createXmlFilePipeline;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:frontend:layout");
        $this->setDescription("Adds a new Layout XML file.");
        $this->addOption(ConfigKey::LAYOUT_XML_HANDLE, null, InputOption::VALUE_OPTIONAL, 'Layout XML handle');
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

        // Gather inputs
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(ConfigKey::LAYOUT_XML_HANDLE)) {
            $question = new Question('<question>Layout XML handle (without .xml suffix): [default]</question> ', 'default');

            $input->setOption(
                ConfigKey::LAYOUT_XML_HANDLE,
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
        $config['create-folder-stage']['folder-path']                 = $this->getAbsolutePathToFolder($input, 'view' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'layout');

        // Create layout.xml Stage config
        $config['create-xml-file-stage']['file-path']          = $config['create-folder-stage']['folder-path'];
        $config['create-xml-file-stage']['file-name']          = strtolower(str_replace('{{LAYOUT_XML_HANDLE}}', $input->getOption(ConfigKey::LAYOUT_XML_HANDLE), '{{LAYOUT_XML_HANDLE}}.xml'));
        $config['create-xml-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-xml-file-stage']['template-file-path'] = $this->getTemplateFilePath('{{LAYOUT_XML_HANDLE}}.xml', 'view' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'layout');

        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        $masterPipelineConfig = [
            'is_valid' => true,
            'config'   => $config,
        ];

        // Run the pipeline
        return $this->createXmlFilePipeline->processPipeline($masterPipelineConfig);
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
            '{{LAYOUT_XML_HANDLE}}' => $input->getOption(ConfigKey::LAYOUT_XML_HANDLE),
        ]);

        return $templateVariables;
    }
}
