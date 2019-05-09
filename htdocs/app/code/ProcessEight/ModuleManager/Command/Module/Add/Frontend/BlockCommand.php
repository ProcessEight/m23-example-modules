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

use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class BlockCommand
 *
 * Creates a block class file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Frontend
 */
class BlockCommand extends Command
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName
     */
    private $validateVendorName;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName
     */
    private $validateModuleName;

    /**
     * @var \Magento\Framework\Module\Dir
     */
    private $moduleDir;
    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateFolder
     */
    private $createFolder;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                  $pipeline
     * @param \Magento\Framework\Module\Dir                              $moduleDir
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName $validateVendorName
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName $validateModuleName
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateFolder       $createFolder
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\Module\Dir $moduleDir,
        \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName $validateVendorName,
        \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName $validateModuleName,
        \ProcessEight\ModuleManager\Model\Stage\CreateFolder $createFolder
    ) {
        parent::__construct();
        $this->pipeline           = $pipeline;
        $this->moduleDir          = $moduleDir;
        $this->validateVendorName = $validateVendorName;
        $this->validateModuleName = $validateModuleName;
        $this->createFolder       = $createFolder;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:frontend:block");
        $this->setDescription("Adds a new block PHP class.");
        $this->addOption(ConfigKey::VENDOR_NAME, null, InputOption::VALUE_OPTIONAL, 'Vendor name');
        $this->addOption(ConfigKey::MODULE_NAME, null, InputOption::VALUE_OPTIONAL, 'Module name');
        $this->addOption(ConfigKey::BLOCK_CLASS_NAME, null, InputOption::VALUE_OPTIONAL, 'Block class name');
        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Gather inputs
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(ConfigKey::VENDOR_NAME)) {
            $question = new Question('<question>Vendor name [ProcessEight]:</question>', 'ProcessEight');

            $input->setOption(
                ConfigKey::VENDOR_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::MODULE_NAME)) {
            $question = new Question('<question>Module name: [Test]</question>', 'Test');

            $input->setOption(
                ConfigKey::MODULE_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::BLOCK_CLASS_NAME)) {
            $question = new Question('<question>Block class name: [Content]</question>', 'Content');

            $input->setOption(
                ConfigKey::BLOCK_CLASS_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        // Validate inputs
        $validationResult = $this->validateInputs([
            ConfigKey::VENDOR_NAME      => $input->getOption(ConfigKey::VENDOR_NAME),
            ConfigKey::MODULE_NAME      => $input->getOption(ConfigKey::MODULE_NAME),
            ConfigKey::BLOCK_CLASS_NAME => $input->getOption(ConfigKey::BLOCK_CLASS_NAME),
        ]);

        if (!$validationResult['is_valid']) {
            $output->writeln($validationResult['validation_message']);

            return 1;
        }

        // Generate assets
        $creationResult = $this->generateAssets([
            ConfigKey::VENDOR_NAME      => $input->getOption(ConfigKey::VENDOR_NAME),
            ConfigKey::MODULE_NAME      => $input->getOption(ConfigKey::MODULE_NAME),
            ConfigKey::BLOCK_CLASS_NAME => $input->getOption(ConfigKey::BLOCK_CLASS_NAME),
        ]);

        foreach ($creationResult['creation_message'] as $message) {
            $output->writeln($message);
        }

        return $creationResult['is_valid'] ? 0 : 1;
    }

    /**
     * Create and run validation pipeline
     *
     * @param string[] $inputs
     *
     * @return mixed[]
     * @todo Move validation pipeline logic into a 'validate module name pipeline' class and inject it, then run it here
     */
    private function validateInputs(array $inputs) : array
    {
        $config             = [
            'data'     => $inputs,
            'is_valid' => true,
        ];
        $validationPipeline = $this->pipeline;
        $validationPipeline = $validationPipeline->pipe($this->validateVendorName);
        $validationPipeline = $validationPipeline->pipe($this->validateModuleName);

        return $validationPipeline->process($config);
    }

    /**
     * Create and run pipeline
     *
     * @param string[] $inputs
     *
     * @return int|mixed
     * @todo Move generation pipeline logic into a 'create module pipeline' class and inject it, then run it here
     */
    private function generateAssets(array $inputs) : array
    {
        $inputs['area-code']      = 'frontend';
        $inputs['path-to-folder'] = $this->moduleDir->getDir(
                $inputs[ConfigKey::VENDOR_NAME] . '_' . $inputs[ConfigKey::MODULE_NAME]
            ) . DIRECTORY_SEPARATOR . 'Block' . DIRECTORY_SEPARATOR;

        $config = [
            'data'     => $inputs,
            'is_valid' => true,
        ];

        $creationPipeline = $this->pipeline;
        // Create Block folder
        $creationPipeline = $creationPipeline->pipe($this->createFolder);
        // Create Block class
//        $creationPipeline = $creationPipeline->pipe($this->createAreaCodeFolder);

        return $creationPipeline->process($config);
    }
}
