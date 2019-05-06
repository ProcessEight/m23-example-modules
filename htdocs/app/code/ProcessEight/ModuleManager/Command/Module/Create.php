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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use ProcessEight\ModuleManager\Model\ConfigKey;

class Create extends Command
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
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateModuleFolder
     */
    private $createModuleFolder;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateEtcFolder
     */
    private $createEtcFolder;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateModuleXmlFile
     */
    private $createModuleXmlFile;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateRegistrationPhpFile
     */
    private $createRegistrationPhpFile;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateComposerJsonFile
     */
    private $createComposerJsonFile;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                         $pipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName        $validateVendorName
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName        $validateModuleName
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateModuleFolder        $createModuleFolder
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateEtcFolder           $createEtcFolder
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateModuleXmlFile       $createModuleXmlFile
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateRegistrationPhpFile $createRegistrationPhpFile
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateComposerJsonFile    $createComposerJsonFile
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName $validateVendorName,
        \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName $validateModuleName,
        \ProcessEight\ModuleManager\Model\Stage\CreateModuleFolder $createModuleFolder,
        \ProcessEight\ModuleManager\Model\Stage\CreateEtcFolder $createEtcFolder,
        \ProcessEight\ModuleManager\Model\Stage\CreateModuleXmlFile $createModuleXmlFile,
        \ProcessEight\ModuleManager\Model\Stage\CreateRegistrationPhpFile $createRegistrationPhpFile,
        \ProcessEight\ModuleManager\Model\Stage\CreateComposerJsonFile $createComposerJsonFile
    ) {
        parent::__construct();
        $this->pipeline                  = $pipeline;
        $this->validateVendorName        = $validateVendorName;
        $this->validateModuleName        = $validateModuleName;
        $this->createModuleFolder        = $createModuleFolder;
        $this->createEtcFolder           = $createEtcFolder;
        $this->createModuleXmlFile       = $createModuleXmlFile;
        $this->createRegistrationPhpFile = $createRegistrationPhpFile;
        $this->createComposerJsonFile    = $createComposerJsonFile;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:create");
        $this->setDescription("Creates a new module with etc/module.xml, registration.php and composer.json files.");
        $this->addOption(ConfigKey::VENDOR_NAME, null, InputOption::VALUE_OPTIONAL, 'Vendor name');
        $this->addOption(ConfigKey::MODULE_NAME, null, InputOption::VALUE_OPTIONAL, 'Module name');
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
            $question = new Question('<question>Vendor name [ProcessEight]:</question> ', 'ProcessEight');

            $input->setOption(
                ConfigKey::VENDOR_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::MODULE_NAME)) {
            $question = new Question('<question>Module name: [Test]</question> ', 'Test');

            $input->setOption(
                ConfigKey::MODULE_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        // Validate inputs
        $validationResult = $this->validateInputs(
            $input->getOption(ConfigKey::VENDOR_NAME),
            $input->getOption(ConfigKey::MODULE_NAME)
        );

        if (!$validationResult['is_valid']) {
            $output->writeln($validationResult['validation_message']);

            return 1;
        }

        // Generate assets
        $creationResult = $this->generateModule(
            $input->getOption(ConfigKey::VENDOR_NAME),
            $input->getOption(ConfigKey::MODULE_NAME)
        );

        foreach ($creationResult['creation_message'] as $message) {
            $output->writeln($message);
        }

        return $creationResult['is_valid'] ? 0 : 1;
    }

    /**
     * Create and run validation pipeline
     *
     * @param string|null $vendorName
     * @param string|null $moduleName
     *
     * @return mixed[]
     * @todo Move validation pipeline logic into a 'validate module name pipeline' class and inject it, then run it here
     *
     */
    private function validateInputs(?string $vendorName, ?string $moduleName) : array
    {
        $config             = [
            'data'     => [
                ConfigKey::VENDOR_NAME => $vendorName,
                ConfigKey::MODULE_NAME => $moduleName,
            ],
            'is_valid' => true,
        ];
        $validationPipeline = $this->pipeline;
        $validationPipeline = $validationPipeline->pipe($this->validateVendorName);
        $validationPipeline = $validationPipeline->pipe($this->validateModuleName);

        return $validationPipeline->process($config);
    }

    /**
     * Create and run generate module pipeline
     *
     * @param string|null $vendorName
     * @param string|null $moduleName
     *
     * @return int|mixed
     * @todo Move generation pipeline logic into a 'create module pipeline' class and inject it, then run it here
     */
    private function generateModule(?string $vendorName, ?string $moduleName)
    {
        $config = [
            'data'     => [
                ConfigKey::VENDOR_NAME => $vendorName,
                ConfigKey::MODULE_NAME => $moduleName,
            ],
            'is_valid' => true,
        ];

        $creationPipeline = $this->pipeline;
        // Create module folder
        $creationPipeline = $creationPipeline->pipe($this->createModuleFolder);
        // Create etc folder
        $creationPipeline = $creationPipeline->pipe($this->createEtcFolder);
        // Create module.xml
        $creationPipeline = $creationPipeline->pipe($this->createModuleXmlFile);
        // Create registration.php
        $creationPipeline = $creationPipeline->pipe($this->createRegistrationPhpFile);
        // Create composer.json
        $creationPipeline = $creationPipeline->pipe($this->createComposerJsonFile);

        return $creationPipeline->process($config);
    }
}
