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

use ProcessEight\ModuleManager\Model\ConfigKey;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class Controller
 *
 * Creates an etc/adminhtml/routes.xml file and Controller/Adminhtml/<controller-directory-name>/<controller-action-name>.php file
 *
 * @package ProcessEight\ModuleManager\Command\Module\Add\Adminhtml
 */
class Controller extends Command
{
    /**
     * @var \League\Pipeline\Pipeline
     */
    private $pipeline;

    /**
     * @var \Magento\Framework\Module\Dir
     */
    private $moduleDir;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName
     */
    private $validateVendorName;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName
     */
    private $validateModuleName;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateAreaCodeFolder
     */
    private $createAreaCodeFolder;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateRoutesXmlFile
     */
    private $createRoutesXmlFile;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateControllerFolder
     */
    private $createControllerFolder;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateControllerClass
     */
    private $createControllerClass;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateMenuXmlFile
     */
    private $createMenuXmlFile;

    /**
     * @var \ProcessEight\ModuleManager\Model\Stage\CreateAclXmlFile
     */
    private $createAclXmlFile;

    /**
     * Constructor.
     *
     * @param \League\Pipeline\Pipeline                                      $pipeline
     * @param \Magento\Framework\Module\Dir                                  $moduleDir
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName     $validateVendorName
     * @param \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName     $validateModuleName
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateAreaCodeFolder   $createAreaCodeFolder
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateRoutesXmlFile    $createRoutesXmlFile
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateControllerFolder $createControllerFolder
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateControllerClass  $createControllerClass
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateMenuXmlFile      $createMenuXmlFile
     * @param \ProcessEight\ModuleManager\Model\Stage\CreateAclXmlFile       $createAclXmlFile
     */
    public function __construct(
        \League\Pipeline\Pipeline $pipeline,
        \Magento\Framework\Module\Dir $moduleDir,
        \ProcessEight\ModuleManager\Model\Stage\ValidateVendorName $validateVendorName,
        \ProcessEight\ModuleManager\Model\Stage\ValidateModuleName $validateModuleName,
        \ProcessEight\ModuleManager\Model\Stage\CreateAreaCodeFolder $createAreaCodeFolder,
        \ProcessEight\ModuleManager\Model\Stage\CreateRoutesXmlFile $createRoutesXmlFile,
        \ProcessEight\ModuleManager\Model\Stage\CreateControllerFolder $createControllerFolder,
        \ProcessEight\ModuleManager\Model\Stage\CreateControllerClass $createControllerClass,
        \ProcessEight\ModuleManager\Model\Stage\CreateMenuXmlFile $createMenuXmlFile,
        \ProcessEight\ModuleManager\Model\Stage\CreateAclXmlFile $createAclXmlFile
    ) {
        parent::__construct();
        $this->pipeline               = $pipeline;
        $this->moduleDir              = $moduleDir;
        $this->validateVendorName     = $validateVendorName;
        $this->validateModuleName     = $validateModuleName;
        $this->createAreaCodeFolder   = $createAreaCodeFolder;
        $this->createRoutesXmlFile    = $createRoutesXmlFile;
        $this->createControllerFolder = $createControllerFolder;
        $this->createControllerClass  = $createControllerClass;
        $this->createMenuXmlFile      = $createMenuXmlFile;
        $this->createAclXmlFile       = $createAclXmlFile;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:module:add:adminhtml:controller");
        $this->setDescription("Creates an etc/adminhtml/routes.xml file and Controller/Adminhtml/<controller-directory-name>/<controller-action-name>.php file.");
        $this->addOption(ConfigKey::VENDOR_NAME, null, InputOption::VALUE_OPTIONAL, 'Vendor name');
        $this->addOption(ConfigKey::MODULE_NAME, null, InputOption::VALUE_OPTIONAL, 'Module name');
        $this->addOption(ConfigKey::FRONT_NAME, null, InputOption::VALUE_OPTIONAL, 'Front name');
        $this->addOption(
            ConfigKey::CONTROLLER_DIRECTORY_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Controller directory name'
        );
        $this->addOption(
            ConfigKey::CONTROLLER_ACTION_NAME,
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

        if (!$input->getOption(ConfigKey::FRONT_NAME)) {
            $question = new Question('<question>Front name (the \'catalog\' in \'/admin/catalog/product/edit\'): [test]</question> ', 'test');

            $input->setOption(
                ConfigKey::FRONT_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::CONTROLLER_DIRECTORY_NAME)) {
            $question = new Question('<question>Controller directory name (the \'product\' in \'/admin/catalog/product/edit\') [index]:</question> ',
                'index');

            $input->setOption(
                ConfigKey::CONTROLLER_DIRECTORY_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        if (!$input->getOption(ConfigKey::CONTROLLER_ACTION_NAME)) {
            $question = new Question('<question>Controller action name (the \'view\' in \'/admin/catalog/product/view\') [view]:</question> ',
                'view');

            $input->setOption(
                ConfigKey::CONTROLLER_ACTION_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }

        // Validate inputs
        $validationResult = $this->validateInputs([
            ConfigKey::VENDOR_NAME               => $input->getOption(ConfigKey::VENDOR_NAME),
            ConfigKey::MODULE_NAME               => $input->getOption(ConfigKey::MODULE_NAME),
            ConfigKey::FRONT_NAME                => $input->getOption(ConfigKey::FRONT_NAME),
            ConfigKey::CONTROLLER_DIRECTORY_NAME => $input->getOption(ConfigKey::CONTROLLER_DIRECTORY_NAME),
            ConfigKey::CONTROLLER_ACTION_NAME    => $input->getOption(ConfigKey::CONTROLLER_ACTION_NAME),
        ]);

        if (!$validationResult['is_valid']) {
            $output->writeln($validationResult['validation_message']);

            return 1;
        }

        // Generate assets
        $creationResult = $this->generateModule([
            ConfigKey::VENDOR_NAME               => $input->getOption(ConfigKey::VENDOR_NAME),
            ConfigKey::MODULE_NAME               => $input->getOption(ConfigKey::MODULE_NAME),
            ConfigKey::FRONT_NAME                => $input->getOption(ConfigKey::FRONT_NAME),
            ConfigKey::CONTROLLER_DIRECTORY_NAME => $input->getOption(ConfigKey::CONTROLLER_DIRECTORY_NAME),
            ConfigKey::CONTROLLER_ACTION_NAME    => $input->getOption(ConfigKey::CONTROLLER_ACTION_NAME),
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
     * @return array
     * @todo Move generation pipeline logic into a 'create module pipeline' class and inject it, then run it here
     */
    private function generateModule(array $inputs) : array
    {
        $inputs['area-code'] = 'adminhtml';
        // Get path to vendor-code/module-name/etc/adminhtml/ folder
        $inputs['path-to-area-code-folder'] = $this->moduleDir->getDir(
            $inputs[ConfigKey::VENDOR_NAME] . '_' . $inputs[ConfigKey::MODULE_NAME],
            \Magento\Framework\Module\Dir::MODULE_ETC_DIR
        ) . DIRECTORY_SEPARATOR . '{{AREA_CODE}}';

        $config = [
            'data'     => $inputs,
            'is_valid' => true,
        ];

        $creationPipeline = $this->pipeline;
        // Create etc/adminhtml folder
        $creationPipeline = $creationPipeline->pipe($this->createAreaCodeFolder);
        // Create routes.xml file
        $creationPipeline = $creationPipeline->pipe($this->createRoutesXmlFile);
        // Create controller folder
        $creationPipeline = $creationPipeline->pipe($this->createControllerFolder);
        // Create controller class
        $creationPipeline = $creationPipeline->pipe($this->createControllerClass);
        // Create etc/acl.xml file
        $creationPipeline = $creationPipeline->pipe($this->createAclXmlFile);
        // Create etc/adminhtml/menu.xml file
        $creationPipeline = $creationPipeline->pipe($this->createMenuXmlFile);

        return $creationPipeline->process($config);
    }
}
