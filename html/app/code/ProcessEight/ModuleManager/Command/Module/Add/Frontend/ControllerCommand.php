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
 * Class Controller
 *
 * Creates an etc/frontend/routes.xml file and Controller/<controller-directory-name>/<controller-action-name>.php file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Frontend
 */
class ControllerCommand extends BaseCommand
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateFrontendControllerPipeline
     */
    private $createControllerPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                                   $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                             $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateFrontendControllerPipeline $createControllerPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateFrontendControllerPipeline $createControllerPipeline
    ) {
        $this->createControllerPipeline = $createControllerPipeline;
        parent::__construct($pipeline, $directoryList);
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:frontend:controller");
        $this->setDescription("Adds a new controller PHP class and routes.xml file.");

        $this->pipelineConfig['mode'] = 'configure';

        $this->pipelineConfig = $this->createControllerPipeline->processPipeline($this->pipelineConfig);

        parent::configure();
    }

    /**
     * Execute the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null null or 0 if everything went fine, or an error code
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        parent::execute($input, $output);

        $this->pipelineConfig['mode'] = 'process';

        $result = $this->createControllerPipeline->processPipeline($this->pipelineConfig);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;
    }
}
