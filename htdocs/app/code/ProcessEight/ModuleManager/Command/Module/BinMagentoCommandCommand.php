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
class BinMagentoCommandCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * Used to generate file name
     */
    const ARTEFACT_FILE_NAME = '{{COMMAND_CLASS_NAME}}.php';

    /**
     * Area code this command is working with
     */
    const AREA_CODE = 'frontend';

    /**
     * @var \League\Pipeline\Pipeline
     */
    private $masterPipeline;

    /**
     * @var \Magento\Framework\Module\Dir
     */
    private $moduleDir;

    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateFolderPipeline
     */
    private $createFolderPipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage
     */
    private $createPhpClassFileStage;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage
     */
    private $createXmlFileStage;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                       $masterPipeline
     * @param \Magento\Framework\Module\Dir                                   $moduleDir
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateFolderPipeline $createFolderPipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage $createPhpClassFileStage
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage      $createXmlFileStage
     */
    public function __construct(
        \League\Pipeline\Pipeline $masterPipeline,
        \Magento\Framework\Module\Dir $moduleDir,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateFolderPipeline $createFolderPipeline,
        \ProcessEight\ModuleManager\Model\Stage\CreatePhpClassFileStage $createPhpClassFileStage,
        \ProcessEight\ModuleManager\Model\Stage\CreateXmlFileStage $createXmlFileStage
    ) {
        parent::__construct();
        $this->masterPipeline          = $masterPipeline;
        $this->moduleDir               = $moduleDir;
        $this->createFolderPipeline    = $createFolderPipeline;
        $this->createPhpClassFileStage = $createPhpClassFileStage;
        $this->createXmlFileStage      = $createXmlFileStage;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:command");
        $this->setDescription("Creates a new bin/magento command.");
        $this->addOption(ConfigKey::VENDOR_NAME, null, InputOption::VALUE_OPTIONAL, 'Vendor name');
        $this->addOption(ConfigKey::MODULE_NAME, null, InputOption::VALUE_OPTIONAL, 'Module name');
        $this->addOption(ConfigKey::COMMAND_NAME, null, InputOption::VALUE_OPTIONAL,
            'Command name, e.g. process-eight:module:create');
        $this->addOption(ConfigKey::COMMAND_DESCRIPTION, null, InputOption::VALUE_OPTIONAL,
            'Brief description of the command');
        $this->addOption(ConfigKey::COMMAND_CLASS_NAME, null, InputOption::VALUE_OPTIONAL, 'Command class name');
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
        // Gather inputs
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

        foreach ($result['creation_message'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;
    }

    /**
     * Define and configure the stages in the pipeline, then execute it
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array
     */
    private function processPipeline(\Symfony\Component\Console\Input\InputInterface $input) : array
    {
        // Stage config
        $data[ConfigKey::VENDOR_NAME] = $input->getOption(ConfigKey::VENDOR_NAME);
        $data[ConfigKey::MODULE_NAME] = $input->getOption(ConfigKey::MODULE_NAME);
        $data['path-to-folder']       = $this->getAbsolutePathToFolder($input, 'Command');
        $stageConfig                  = [
            'data' => $data,
        ];
        $this->createFolderPipeline->setConfig($stageConfig);

        // Create PHP Class Stage config

        // Replace template variable in file name
        $artefactFileName  = str_replace(
            '{{COMMAND_CLASS_NAME}}',
            $input->getOption(ConfigKey::COMMAND_CLASS_NAME),
            self::ARTEFACT_FILE_NAME
        );
        $templateVariables = [
            '{{VENDOR_NAME}}'         => $input->getOption(ConfigKey::VENDOR_NAME),
            '{{MODULE_NAME}}'         => $input->getOption(ConfigKey::MODULE_NAME),
            '{{COMMAND_NAME}}'        => $input->getOption(ConfigKey::COMMAND_NAME),
            '{{COMMAND_DESCRIPTION}}' => $input->getOption(ConfigKey::COMMAND_DESCRIPTION),
            '{{COMMAND_CLASS_NAME}}'  => $input->getOption(ConfigKey::COMMAND_CLASS_NAME),
            '{{YEAR}}'                => date('Y'),
        ];

        $templateFilePath = implode(DIRECTORY_SEPARATOR, [
            $this->moduleDir->getDir('ProcessEight_ModuleManager'),
            'Template',
            'Command',
            self::ARTEFACT_FILE_NAME . '.template',
        ]);

        $this->createPhpClassFileStage->setFileName($artefactFileName);
        $this->createPhpClassFileStage->setFilePath($this->getAbsolutePathToFolder($input,'Command'));
        $this->createPhpClassFileStage->setTemplateFilePath($templateFilePath);
        $this->createPhpClassFileStage->setTemplateVariables($templateVariables);

        // Create di.xml Stage config

        // Replace template variable in file name
        $artefactFileName  = 'di.xml';
        $templateVariables = [
            '{{VENDOR_NAME}}'                  => $input->getOption(ConfigKey::VENDOR_NAME),
            '{{MODULE_NAME}}'                  => $input->getOption(ConfigKey::MODULE_NAME),
            '{{VENDOR_NAME_LOWERCASE}}'        => strtolower($input->getOption(ConfigKey::VENDOR_NAME)),
            '{{MODULE_NAME_LOWERCASE}}'        => strtolower($input->getOption(ConfigKey::MODULE_NAME)),
            '{{COMMAND_CLASS_NAME}}'           => $input->getOption(ConfigKey::COMMAND_CLASS_NAME),
            '{{COMMAND_CLASS_NAME_LOWERCASE}}' => strtolower($input->getOption(ConfigKey::COMMAND_CLASS_NAME)),
            '{{YEAR}}'                         => date('Y'),
        ];

        $templateFilePath = implode(DIRECTORY_SEPARATOR, [
            $this->moduleDir->getDir('ProcessEight_ModuleManager'),
            'Template',
            'etc',
            'di.xml.template',
        ]);

        $this->createXmlFileStage->setFileName($artefactFileName);
        $this->createXmlFileStage->setFilePath($this->getAbsolutePathToFolder($input,'etc'));
        $this->createXmlFileStage->setTemplateFilePath($templateFilePath);
        $this->createXmlFileStage->setTemplateVariables($templateVariables);

        // Add the pipelines/stages we need for this command
        $masterPipeline = $this->masterPipeline
            // Create the folder
            ->pipe($this->createFolderPipeline)
            // Create the class
            ->pipe($this->createPhpClassFileStage)
            // Create the di.xml
            ->pipe($this->createXmlFileStage);

        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        $masterPipelineConfig = [
            'is_valid' => true,
        ];

        // Run the pipeline
        return $masterPipeline->process($masterPipelineConfig);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string                                          $trailingPath
     *
     * @return string
     */
    private function getAbsolutePathToFolder(
        \Symfony\Component\Console\Input\InputInterface $input,
        string $trailingPath = ''
    ) : string {
        return $this->moduleDir->getDir(
            $input->getOption(ConfigKey::VENDOR_NAME) . '_' . $input->getOption(ConfigKey::MODULE_NAME)
        ) . DIRECTORY_SEPARATOR . $trailingPath;
    }
}
