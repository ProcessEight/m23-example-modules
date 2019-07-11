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

namespace ProcessEight\ModuleManager\Command\Module\Add\Adminhtml;

use ProcessEight\ModuleManager\Command\BaseCommand;
use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class TemplateCommand
 *
 * Creates a view/adminhtml/template/<template-name>.phtml file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Adminhtml
 */
class TemplateCommand extends BaseCommand
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateAreaCodeFolder
     */
    private $createAreaCodeFolder;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateTemplatePhtmlFile
     */
    private $createTemplatePhtmlFile;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                       $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                 $directoryList
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateAreaCodeFolder    $createAreaCodeFolder
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateTemplatePhtmlFile $createTemplatePhtmlFile
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Stage\CreateAreaCodeFolder $createAreaCodeFolder,
        \ProcessEight\ModuleManager\Model\Stage\CreateTemplatePhtmlFile $createTemplatePhtmlFile
    ) {
        parent::__construct($pipeline, $directoryList);
        $this->pipeline                = $pipeline;
        $this->createAreaCodeFolder    = $createAreaCodeFolder;
        $this->createTemplatePhtmlFile = $createTemplatePhtmlFile;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        parent::configure();
        $this->setName("process-eight:module:add:adminhtml:template");
        $this->setDescription("Adds a new template PHTML file to the adminhtml area.");
        $this->addOption(ConfigKey::TEMPLATE_NAME, null, InputOption::VALUE_OPTIONAL, 'Template PHTML name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        // Gather inputs
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(ConfigKey::TEMPLATE_NAME)) {
            $question = new Question(
                '<question>Template PHTML name: [content]</question> ',
                'content'
            );

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
