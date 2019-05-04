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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Controller extends Command
{
    const VENDOR_NAME = 'vendor-name';
    const MODULE_NAME = 'module-name';
    const FRONT_NAME = 'front-name';
    const CONTROLLER_DIRECTORY_NAME = 'controller-directory-name';
    const CONTROLLER_ACTION_NAME = 'controller-action-name';

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
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateRoutesXmlFile
     */
    private $createRoutesXmlFile;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateControllerFolder
     */
    private $createControllerFolder;

    /**
     * Create constructor.
     *
     * @param \League\Pipeline\Pipeline                                      $pipeline
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName     $validateVendorName
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName     $validateModuleName
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateRoutesXmlFile    $createRoutesXmlFile
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateControllerFolder $createControllerFolder
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName $validateVendorName,
        \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName $validateModuleName,
        \ProcessEight\ModuleManager\Model\Stage\CreateRoutesXmlFile $createRoutesXmlFile,
        \ProcessEight\ModuleManager\Model\Stage\CreateControllerFolder $createControllerFolder
    ) {
        parent::__construct();
        $this->pipeline               = $pipeline;
        $this->validateVendorName     = $validateVendorName;
        $this->validateModuleName     = $validateModuleName;
        $this->createRoutesXmlFile    = $createRoutesXmlFile;
        $this->createControllerFolder = $createControllerFolder;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:frontend:controller");
        $this->setDescription("Adds a new controller PHP class and routes.xml file.");
        $this->addOption(self::VENDOR_NAME, null, InputOption::VALUE_OPTIONAL, 'Vendor name');
        $this->addOption(self::MODULE_NAME, null, InputOption::VALUE_OPTIONAL, 'Module name');
        $this->addOption(self::FRONT_NAME, null, InputOption::VALUE_OPTIONAL, 'Front name');
        $this->addOption(
            self::CONTROLLER_DIRECTORY_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Controller directory name'
        );
        $this->addOption(
            self::CONTROLLER_ACTION_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Controller action name'
        );
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

        if (!$input->getOption(self::FRONT_NAME)) {
            $question = new Question('<question>Front name (the \'catalog\' in \'/catalog/product/view\'):</question> ');

            $input->setOption(
                self::FRONT_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(self::CONTROLLER_DIRECTORY_NAME)) {
            $question = new Question('<question>Controller directory name (the \'product\' in \'/catalog/product/view\') [Index]:</question> ',
                'Index');

            $input->setOption(
                self::CONTROLLER_DIRECTORY_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(self::CONTROLLER_ACTION_NAME)) {
            $question = new Question('<question>Controller action name (the \'view\' in \'/catalog/product/view\') [View]:</question> ',
                'View');

            $input->setOption(
                self::CONTROLLER_ACTION_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        // Validate inputs
        $validationResult = $this->validateInputs([
            self::VENDOR_NAME               => $input->getOption(self::VENDOR_NAME),
            self::MODULE_NAME               => $input->getOption(self::MODULE_NAME),
            self::FRONT_NAME                => $input->getOption(self::FRONT_NAME),
            self::CONTROLLER_DIRECTORY_NAME => $input->getOption(self::CONTROLLER_DIRECTORY_NAME),
            self::CONTROLLER_ACTION_NAME    => $input->getOption(self::CONTROLLER_ACTION_NAME),
        ]);

        if (!$validationResult['is_valid']) {
            $output->writeln($validationResult['validation_message']);

            return 1;
        }

        // Generate assets
        $creationResult = $this->generateModule([
            self::VENDOR_NAME               => $input->getOption(self::VENDOR_NAME),
            self::MODULE_NAME               => $input->getOption(self::MODULE_NAME),
            self::FRONT_NAME                => $input->getOption(self::FRONT_NAME),
            self::CONTROLLER_DIRECTORY_NAME => $input->getOption(self::CONTROLLER_DIRECTORY_NAME),
            self::CONTROLLER_ACTION_NAME    => $input->getOption(self::CONTROLLER_ACTION_NAME),
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
     * Create and run generate module pipeline
     *
     * @param string[] $inputs
     *
     * @return int|mixed
     * @todo Move generation pipeline logic into a 'create module pipeline' class and inject it, then run it here
     */
    private function generateModule(array $inputs) : array
    {
        $inputs['area-code'] = 'frontend';
        $config              = [
            'data'     => $inputs,
            'is_valid' => true,
        ];

        $creationPipeline = $this->pipeline;
        // Create controller folder
        $creationPipeline = $creationPipeline->pipe($this->createControllerFolder);
        // Create controller directory folder
//        $creationPipeline = $creationPipeline->pipe($this->createEtcFolder);
        // Create controller class
//        $creationPipeline = $creationPipeline->pipe($this->createModuleXmlFile);
        // Create etc/frontend folder
//        $creationPipeline = $creationPipeline->pipe($this->createRegistrationPhpFile);
        // Create routes.xml file
        $creationPipeline = $creationPipeline->pipe($this->createRoutesXmlFile);

        return $creationPipeline->process($config);
    }
}
