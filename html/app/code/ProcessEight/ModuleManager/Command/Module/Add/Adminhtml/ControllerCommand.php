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
 * Class ControllerCommand
 *
 * Creates an etc/adminhtml/routes.xml file and Controller/Adminhtml/CONTROLLER_DIRECTORY_NAME/CONTROLLER_ACTION_NAME.php file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Adminhtml
 */
class ControllerCommand extends BaseCommand
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlControllerPipeline
     */
    private $createAdminhtmlControllerPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                                              $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                                        $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlControllerPipeline $createAdminhtmlControllerPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlControllerPipeline $createAdminhtmlControllerPipeline
    ) {
        $this->createAdminhtmlControllerPipeline = $createAdminhtmlControllerPipeline;
        parent::__construct($pipeline, $directoryList);
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:adminhtml:controller");
        $this->setDescription("Creates an etc/adminhtml/routes.xml file and Controller/Adminhtml/CONTROLLER_DIRECTORY_NAME/CONTROLLER_ACTION_NAME.php file.");

        $this->pipelineConfig['mode'] = 'configure';

        $this->pipelineConfig = $this->createAdminhtmlControllerPipeline->processPipeline($this->pipelineConfig);

        parent::configure();

        return null;
    }

    /**
     * Execute the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->pipelineConfig['mode'] = 'process';

        $result = $this->createAdminhtmlControllerPipeline->processPipeline($this->pipelineConfig);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;
    }
}
