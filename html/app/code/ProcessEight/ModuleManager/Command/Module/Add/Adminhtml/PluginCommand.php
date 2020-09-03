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

namespace ProcessEight\ModuleManager\Command\Module\Add\Adminhtml;

use ProcessEight\ModuleManager\Command\BaseCommand;
use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class PluginCommand
 *
 * Creates an etc/adminhtml/di.xml file and Plugin/Adminhtml/<plugin-class-name>.php file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Adminhtml
 */
class PluginCommand extends BaseCommand
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateAdminhtmlPluginPipeline
     */
    private $createAdminhtmlPluginPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                                $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                          $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateAdminhtmlPluginPipeline $createAdminhtmlPluginPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateAdminhtmlPluginPipeline $createAdminhtmlPluginPipeline
    ) {
        parent::__construct($pipeline, $directoryList);

        $this->createAdminhtmlPluginPipeline = $createAdminhtmlPluginPipeline;
    }

    /**
     * Ask for command-specific data
     */
    protected function configure()
    {
        parent::configure();
        $this->setName("process-eight:module:add:adminhtml:plugin");
        $this->setDescription("Creates an etc/adminhtml/di.xml file and Plugin/Adminhtml/<plugin-class-name>.php file");
        $this->addOption(
            ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE,
            null,
            InputOption::VALUE_REQUIRED,
            'Method to intercept (in format \Vendor\Namespace\Path\To\Class::methodToIntercept)'
        );
        $this->addOption(
            ConfigKey::PLUGIN_TYPE,
            null,
            InputOption::VALUE_REQUIRED,
            'Plugin type (before, around, after)'
        );
        $this->addOption(
            ConfigKey::PLUGIN_AREA,
            null,
            InputOption::VALUE_REQUIRED,
            'Plugin area (global, adminhtml, frontend, webapi_rest, webapi_soap)'
        );
        $this->addOption(
            ConfigKey::PLUGIN_CLASS_NAME,
            null,
            InputOption::VALUE_REQUIRED,
            'Plugin class name'
        );
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        // Gather inputs
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE)) {
            $question = new Question(
                '<question>Method to intercept (in format \Vendor\Namespace\Path\To\Class::methodToIntercept): [\Magento\Backend\Block\Widget\Button\Toolbar::pushButtons]</question> ',
                '\Magento\Backend\Block\Widget\Button\Toolbar::pushButtons' // Just for testing, you understand. Remove later.
            );

            $input->setOption(
                ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::PLUGIN_TYPE)) {
            $question = new Question(
                '<question>Plugin type: [before]:</question> ',
                'before'
            );

            $input->setOption(
                ConfigKey::PLUGIN_TYPE,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::PLUGIN_AREA)) {
            $question = new Question(
                '<question>Plugin area: [adminhtml]:</question> ',
                'adminhtml'
            );

            $input->setOption(
                ConfigKey::PLUGIN_AREA,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::PLUGIN_CLASS_NAME)) {
            $question = new Question(
                '<question>Plugin class name: [ToolbarPlugin]:</question> ',
                'ToolbarPlugin'
            );

            $input->setOption(
                ConfigKey::PLUGIN_CLASS_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        $result             = $this->processPipeline($input);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;
    }

    /**
     * Prepare all the data needed to run all the Stages/Pipelines needed for this command, then execute the Pipeline
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function processPipeline(\Symfony\Component\Console\Input\InputInterface $input) : array
    {
        /*
         * Refactor to structure the array thusly (which will allow passing different values to one stage,
         * or using multiple instances of the same stage to process different values, i.e. Using two instances of CreateFolderStage
         * to create two different folders):
         * config = [
         *      'folders' = [
         *          // Array key here is added purely for reference
         *          'controller' => 'Controller/Example',
         *          'etc'        => 'etc/frontend',
         *      ],
         *      'files' => [
         *          // Thought: The array key here could be used to tell the stage what type of file to create?
         *          'controller' => 'Controller/Example/Index.php,
         *      ]
         * ]
         * Then, in CreateFolderStage (or whatever), loop through the config['folders'] and config['files']
         */

        // validateModuleNamePipeline config
        $config['validate-vendor-name-stage'][ConfigKey::VENDOR_NAME] = $input->getOption(ConfigKey::VENDOR_NAME);
        $config['validate-module-name-stage'][ConfigKey::MODULE_NAME] = $input->getOption(ConfigKey::MODULE_NAME);

        // createModuleFolderStage config
        $config['create-folder-stage']['folder-path'] = $this->getAbsolutePathToFolder(
            $input,
            'Plugin' . DIRECTORY_SEPARATOR
        );

        // createPhpClassStage config
        $config['create-php-class-file-stage']['file-path']          = $config['create-folder-stage']['folder-path'];
        $config['create-php-class-file-stage']['file-name']          = ucwords(str_replace('{{PLUGIN_CLASS_NAME}}', $input->getOption(ConfigKey::PLUGIN_CLASS_NAME), '{{PLUGIN_CLASS_NAME}}.php'));

        $config['create-php-class-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-php-class-file-stage']['template-file-path'] = $this->getTemplateFilePath('{{PLUGIN_CLASS_NAME}}.php', 'Plugin');

        // createAreaCodeFolderStage config
        $config['create-area-code-folder-stage']['path-to-area-code-folder'] = $this->getAbsolutePathToFolder(
            $input,
            'etc' . DIRECTORY_SEPARATOR . 'adminhtml'
        );
        $config['create-area-code-folder-stage']['area-code'] = strtolower($input->getOption(ConfigKey::PLUGIN_AREA));

        // createXmlFileStage config
        $config['create-xml-file-stage']['file-path']          = $config['create-area-code-folder-stage']['path-to-area-code-folder'];
        $config['create-xml-file-stage']['file-name']          = 'di.xml';
        $config['create-xml-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-xml-file-stage']['template-file-path'] = $this->getTemplateFilePath(
            $config['create-xml-file-stage']['file-name'],
            'etc' . DIRECTORY_SEPARATOR . 'adminhtml'
        );

        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        $masterPipelineConfig = [
            'is_valid' => true,
            'config'   => $config,
        ];

        print_r($masterPipelineConfig);

        // Run the pipeline
        return $this->createAdminhtmlPluginPipeline->processPipeline($masterPipelineConfig);
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
        $parentTemplateVariables = parent::getTemplateVariables($input);

        $parts                 = explode('::', $input->getOption(ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE));
        $noVendorNameClassPath = explode('\\', $parts[0]);
        unset($noVendorNameClassPath[0], $noVendorNameClassPath[1]);
        $interceptedClassName = array_pop($noVendorNameClassPath);

        $templateVariables = [
            '{{PLUGIN_ORIGINAL_CLASS_PATH}}'  => trim($parts[0], '\\'),
            '{{PLUGIN_CLASS_NAME}}'  => $interceptedClassName . 'Plugin',
            '{{PLUGIN_METHOD_NAME}}' => $input->getOption(ConfigKey::PLUGIN_TYPE) . ucfirst($parts[1]),
            '{{PLUGIN_TYPE}}'        => $input->getOption(ConfigKey::PLUGIN_TYPE),
            '{{AREA_CODE}}' => strtolower($input->getOption(ConfigKey::PLUGIN_AREA)),
        ];

        return array_merge($templateVariables, $parentTemplateVariables);
    }
}
