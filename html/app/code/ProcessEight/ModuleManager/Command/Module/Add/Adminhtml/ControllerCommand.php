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
 * Class ControllerCommand
 *
 * Creates an etc/adminhtml/routes.xml file and Controller/Adminhtml/<controller-directory-name>/<controller-action-name>.php file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Adminhtml
 */
class ControllerCommand extends BaseCommand
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateAdminhtmlControllerPipeline
     */
    private $createAdminhtmlControllerPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                                    $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                              $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateAdminhtmlControllerPipeline $createAdminhtmlControllerPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateAdminhtmlControllerPipeline $createAdminhtmlControllerPipeline
    ) {
        parent::__construct($pipeline, $directoryList);

        $this->pipeline                          = $pipeline;
        $this->createAdminhtmlControllerPipeline = $createAdminhtmlControllerPipeline;
    }

    /**
     * Ask for command-specific data
     */
    protected function configure()
    {
        parent::configure();
        $this->setName("process-eight:module:add:adminhtml:controller");
        $this->setDescription("Creates an etc/adminhtml/routes.xml file and Controller/Adminhtml/<controller-directory-name>/<controller-action-name>.php file.");
        $this->addOption(ConfigKey::FRONT_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Front name'
        );
        $this->addOption(
            ConfigKey::CONTROLLER_DIRECTORY_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Controller directory name'
        );
        $this->addOption(
            ConfigKey::CONTROLLER_ACTION_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Controller action name'
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

        if (!$input->getOption(ConfigKey::FRONT_NAME)) {
            $question = new Question(
                '<question>Front name (the \'catalog\' in \'/admin/catalog/product/edit\'): [custom]</question> ',
                'custom'
            );

            $input->setOption(
                ConfigKey::FRONT_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::CONTROLLER_DIRECTORY_NAME)) {
            $question = new Question(
                '<question>Controller directory name (the \'product\' in \'/admin/catalog/product/edit\') [example]:</question> ',
                'example'
            );

            $input->setOption(
                ConfigKey::CONTROLLER_DIRECTORY_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::CONTROLLER_ACTION_NAME)) {
            $question = new Question(
                '<question>Controller action name (the \'view\' in \'/admin/catalog/product/view\') [index]:</question> ',
                'index'
            );

            $input->setOption(
                ConfigKey::CONTROLLER_ACTION_NAME,
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
        $config['create-folder-stage']['folder-path'] = $this->getAbsolutePathToFolder($input,'Controller' . DIRECTORY_SEPARATOR . 'Adminhtml' . DIRECTORY_SEPARATOR . ucfirst($input->getOption(ConfigKey::CONTROLLER_DIRECTORY_NAME)));

        // createPhpClassStage config
        $config['create-php-class-file-stage']['file-path']          = $config['create-folder-stage']['folder-path'];
        $config['create-php-class-file-stage']['file-name']          = ucfirst(str_replace('{{CONTROLLER_ACTION_NAME_UCFIRST}}',$input->getOption(ConfigKey::CONTROLLER_ACTION_NAME), '{{CONTROLLER_ACTION_NAME_UCFIRST}}.php'));
        $config['create-php-class-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-php-class-file-stage']['template-file-path'] = $this->getTemplateFilePath('{{CONTROLLER_ACTION_NAME_UCFIRST}}.php','Controller' . DIRECTORY_SEPARATOR . 'Adminhtml');

        $config['create-etc-folder-stage']['folder-path']    = $this->getAbsolutePathToFolder($input,'etc' . DIRECTORY_SEPARATOR . 'adminhtml');

        // createXmlFileStage config
        $config['create-xml-file-stage']['file-path']          = $config['create-etc-folder-stage']['folder-path'];
        $config['create-xml-file-stage']['file-name']          = 'routes.xml';
        $config['create-xml-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-xml-file-stage']['template-file-path'] = $this->getTemplateFilePath('routes.xml','etc' . DIRECTORY_SEPARATOR . 'adminhtml');

        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        $masterPipelineConfig = [
            'is_valid' => true,
            'config'   => $config,
        ];

        // Run the pipeline
        return $this->createAdminhtmlControllerPipeline->processPipeline($masterPipelineConfig);
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
        $templateVariables       = [
            '{{FRONT_NAME}}'                        => $input->getOption(ConfigKey::FRONT_NAME),
            '{{CONTROLLER_DIRECTORY_NAME}}'         => $input->getOption(ConfigKey::CONTROLLER_DIRECTORY_NAME),
            '{{CONTROLLER_DIRECTORY_NAME_UCFIRST}}' => ucfirst($input->getOption(ConfigKey::CONTROLLER_DIRECTORY_NAME)),
            '{{CONTROLLER_ACTION_NAME}}'            => $input->getOption(ConfigKey::CONTROLLER_ACTION_NAME),
            '{{CONTROLLER_ACTION_NAME_UCFIRST}}'    => ucfirst($input->getOption(ConfigKey::CONTROLLER_ACTION_NAME)),
        ];

        return array_merge($templateVariables, $parentTemplateVariables);
    }
}
