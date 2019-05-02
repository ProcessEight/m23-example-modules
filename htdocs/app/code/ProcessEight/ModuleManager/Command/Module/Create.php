<?php
/**
 * Process Eight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 Process Eight
 * @author      Process Eight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ModuleManager\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Create extends Command
{
    const VENDOR_NAME = 'vendor-name';
    const MODULE_NAME = 'module-name';

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
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateFolder
     */
    private $createFolder;

    /**
     * Create constructor.
     *
     * @param \League\Pipeline\Pipeline                                  $pipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName $validateVendorName
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName $validateModuleName
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateFolder       $createFolder
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName $validateVendorName,
        \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName $validateModuleName,
        \ProcessEight\ModuleManager\Model\Stage\CreateFolder $createFolder
    ) {
        parent::__construct();
        $this->pipeline           = $pipeline;
        $this->validateVendorName = $validateVendorName;
        $this->createFolder       = $createFolder;
        $this->validateModuleName = $validateModuleName;
    }

    protected function configure()
    {
        $this->setName("process-eight:module:create");
        $this->setDescription("Creates a new module with etc/module.xml, registration.php and composer.json files.");
        $this->addOption(self::VENDOR_NAME, null, InputOption::VALUE_OPTIONAL, 'Vendor name');
        $this->addOption(self::MODULE_NAME, null, InputOption::VALUE_OPTIONAL, 'Module name');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Gather inputs
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (!$input->getOption(self::VENDOR_NAME)) {
            $question = new Question('<question>Vendor name [ProcessEight]:</question> ', 'ProcessEight');

            $input->setOption(
                self::VENDOR_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(self::MODULE_NAME)) {
            $question = new Question('<question>Module name:</question> ');

            $input->setOption(
                self::MODULE_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        // Validate inputs
        $result = $this->validateInputs(
            $input->getOption(self::VENDOR_NAME),
            $input->getOption(self::MODULE_NAME)
        );

        if (!$result['is_valid']) {
            $output->writeln($result['validation_message']);

            return 1;
        }

        // Generate assets
        $this->generateModule(
            $input->getOption(self::VENDOR_NAME),
            $input->getOption(self::MODULE_NAME)
        );

        // Debugging
        return $output->writeln($input->getOption(self::VENDOR_NAME) . '_' . $input->getOption(self::MODULE_NAME));
    }

    /**
     * Create and run validation pipeline
     *
     * @param string|null $vendorName
     * @param string|null $moduleName
     *
     * @return mixed[]
     */
    private function validateInputs(?string $vendorName, ?string $moduleName) : array
    {
        $config             = [
            'data'     => [
                self::VENDOR_NAME => $vendorName,
                self::MODULE_NAME => $moduleName,
            ],
            'is_valid' => true,
        ];
        $validationPipeline = $this->pipeline;
        $validationPipeline = $validationPipeline->pipe($this->validateVendorName);
        $validationPipeline = $validationPipeline->pipe($this->validateModuleName);

        $config = $validationPipeline->process($config);

        return $config;
    }

    /**
     * Create and run generate module pipeline
     *
     * @param string|null $vendorName
     * @param string|null $moduleName
     *
     * @return int|mixed
     */
    private function generateModule(?string $vendorName, ?string $moduleName)
    {
        $config[self::VENDOR_NAME] = $vendorName;
        $config[self::MODULE_NAME] = $moduleName;

        $creationPipeline = $this->pipeline;
        $creationPipeline->pipe(
            // Create module folder
            $this->createFolder
        )->pipe(
            // Create etc folder
            $this->createFolder
        )->pipe(
            // Create module.xml
            $this->createFolder
        )->pipe(
            // Create registration.php
            $this->createFolder
        )->pipe(
            // Create composer.json
            $this->createFolder
        );

        $config = $creationPipeline->process($config);

        return $config;
    }
}
