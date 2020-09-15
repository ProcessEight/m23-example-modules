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

namespace ProcessEight\ModuleManager\Command\Module\Create\Adminhtml;

use ProcessEight\ModuleManager\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PluginCommand
 *
 * Creates an etc/adminhtml/di.xml file and Plugin/PLUGIN_CLASS_NAME.php file
 *
 */
class PluginCommand extends BaseCommand
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlPluginPipeline
     */
    private $createAdminhtmlPluginPipeline;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                                          $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                                    $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlPluginPipeline $createAdminhtmlPluginPipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\Adminhtml\CreateAdminhtmlPluginPipeline $createAdminhtmlPluginPipeline
    ) {
        $this->createAdminhtmlPluginPipeline = $createAdminhtmlPluginPipeline;
        parent::__construct($pipeline, $directoryList);
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName("process-eight:module:create:adminhtml:plugin");
        $this->setDescription("Creates an etc/adminhtml/di.xml file and Plugin/Adminhtml/PLUGIN_CLASS_NAME.php file");

        $this->pipelineConfig['mode'] = 'configure';

        $this->pipelineConfig = $this->createAdminhtmlPluginPipeline->processPipeline($this->pipelineConfig);

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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->pipelineConfig['mode'] = 'process';

        $result = $this->createAdminhtmlPluginPipeline->processPipeline($this->pipelineConfig);

        foreach ($result['messages'] as $message) {
            $output->writeln($message);
        }

        return $result['is_valid'] ? 0 : 1;
    }

    /**
     * All template variables used in all Stages/Pipelines used by this command
     *
     * @param string $stageId
     *
     * @return array
     */
    public function getTemplateVariables(string $stageId) : array
    {

//        $parts                 = explode('::', $input->getOption(ConfigKey::METHOD_TO_INTERCEPT_NAMESPACE));
//        $noVendorNameClassPath = explode('\\', $parts[0]);
//        unset($noVendorNameClassPath[0], $noVendorNameClassPath[1]);
//        $interceptedClassName = array_pop($noVendorNameClassPath);

//        $templateVariables = [
//            '{{PLUGIN_ORIGINAL_CLASS_PATH}}' => trim($parts[0], '\\'),
//            '{{PLUGIN_CLASS_NAME}}'          => $interceptedClassName . 'Plugin',
//            '{{PLUGIN_METHOD_NAME}}'         => $input->getOption(ConfigKey::PLUGIN_TYPE) . ucfirst($parts[1]),
//            '{{PLUGIN_TYPE}}'                => $input->getOption(ConfigKey::PLUGIN_TYPE),
//            '{{AREA_CODE}}'                  => strtolower($input->getOption(ConfigKey::PLUGIN_AREA)),
//        ];

        return []; //array_merge($templateVariables, $parentTemplateVariables);
    }
}
