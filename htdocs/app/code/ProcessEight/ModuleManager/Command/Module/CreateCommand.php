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

use ProcessEight\ModuleManager\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ProcessEight\ModuleManager\Model\ConfigKey;

class CreateCommand extends BaseCommand
{
    /**
     * @var \ProcessEight\ModuleManager\Model\Pipeline\CreateModulePipeline
     */
    private $createModulePipeline;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                       $pipeline
     * @param \Magento\Framework\App\Filesystem\DirectoryList                 $directoryList
     * @param \ProcessEight\ModuleManager\Model\Pipeline\CreateModulePipeline $createModulePipeline
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \ProcessEight\ModuleManager\Model\Pipeline\CreateModulePipeline $createModulePipeline
    ) {
        parent::__construct($pipeline, $directoryList);
        $this->createModulePipeline = $createModulePipeline;
        $this->directoryList        = $directoryList;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:create");
        $this->setDescription("Creates a new module with etc/module.xml, registration.php and composer.json files.");
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
        // CreateFolderPipeline config
        $config['validate-vendor-name-stage'][ConfigKey::VENDOR_NAME] = $input->getOption(ConfigKey::VENDOR_NAME);
        $config['validate-module-name-stage'][ConfigKey::MODULE_NAME] = $input->getOption(ConfigKey::MODULE_NAME);
        $config['create-module-folder-stage']['folder-path']          = $this->getAbsolutePathToFolder($input);
        $config['create-etc-folder-stage']['folder-path']             = $this->getAbsolutePathToFolder($input, 'etc');

        // Create module.xml stage config
        $config['create-xml-file-stage']['file-path']          = $this->getAbsolutePathToFolder($input, 'etc');
        $config['create-xml-file-stage']['file-name']          = 'module.xml';
        $config['create-xml-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-xml-file-stage']['template-file-path'] = $this->getTemplateFilePath('module.xml', 'etc');

        // Create composer.json Stage config
        $config['create-composer-json-file-stage']['file-path']          = $this->getAbsolutePathToFolder($input);
        $config['create-composer-json-file-stage']['file-name']          = 'composer.json';
        $config['create-composer-json-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-composer-json-file-stage']['template-file-path'] = $this->getTemplateFilePath('composer.json');

        // Create registration.php Stage config
        $config['create-registration-php-file-stage']['file-path']          = $this->getAbsolutePathToFolder($input);
        $config['create-registration-php-file-stage']['file-name']          = 'registration.php';
        $config['create-registration-php-file-stage']['template-variables'] = $this->getTemplateVariables($input);
        $config['create-registration-php-file-stage']['template-file-path'] = $this->getTemplateFilePath('registration.php');

        // Validation flag. Will terminate pipeline if set to false by any pipeline/stage.
        $masterPipelineConfig = [
            'is_valid' => true,
            'config'   => $config,
        ];

        // Run the pipeline
        return $this->createModulePipeline->processPipeline($masterPipelineConfig);
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
        return $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP)
               . DIRECTORY_SEPARATOR . 'code'
               . DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::VENDOR_NAME)
               . DIRECTORY_SEPARATOR . $input->getOption(ConfigKey::MODULE_NAME)
               . DIRECTORY_SEPARATOR . $trailingPath;
    }
}
