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
 * @copyright   Copyright (c) 2020 ProcessEight
 * @author      ProcessEight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Command\Module\Add\Adminhtml;

use ProcessEight\ModuleManager\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ObserverCommand
 *
 * Creates an etc/adminhtml/events.xml file and Observer/OBSERVER_DIRECTORY_NAME/OBSERVER_CLASS_NAME.php file
 *
 */
class ObserverCommand extends BaseCommand
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlObserverPipeline
     */
    private $createAdminhtmlObserverPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                                            $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                                      $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlObserverPipeline $createAdminhtmlObserverPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlObserverPipeline $createAdminhtmlObserverPipeline
    ) {
        $this->createAdminhtmlObserverPipeline = $createAdminhtmlObserverPipeline;
        parent::__construct($pipeline, $directoryList);
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:adminhtml:observer");
        $this->setDescription("Adds a new observer PHP class and etc/adminhtml/events.xml file.");

        $this->pipelineConfig['mode'] = 'configure';

        $this->pipelineConfig = $this->createAdminhtmlObserverPipeline->processPipeline($this->pipelineConfig);

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

        $result = $this->createAdminhtmlObserverPipeline->processPipeline($this->pipelineConfig);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;
    }
}
