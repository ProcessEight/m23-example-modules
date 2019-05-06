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

namespace ProcessEight\GetDirectoryPathsExample\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetDirectoryPathsExample extends Command
{
    const MODULE_NAME = 'ProcessEight_GetDirectoryPathsExample';

    /**
     * @var \Magento\Framework\Module\Dir
     */
    private $moduleDir;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Component\ComponentRegistrarInterface
     */
    private $componentRegistrar;

    /**
     * GetDirectoryPathsExample constructor.
     *
     * @param \Magento\Framework\Module\Dir                            $moduleDir
     * @param \Magento\Framework\App\Filesystem\DirectoryList          $directoryList
     * @param \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar
     */
    public function __construct(
        \Magento\Framework\Module\Dir $moduleDir,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar
    ) {
        parent::__construct();

        $this->moduleDir          = $moduleDir;
        $this->directoryList      = $directoryList;
        $this->componentRegistrar = $componentRegistrar;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName("process-eight:example:get-directory-paths");
        $this->setDescription("Demonstrates how to programmatically retrieve paths to several types of Magento-specific directory (e.g. `base`, `media`, `var`, etc).");
        parent::configure();
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void null or 0 if everything went fine, or an error code
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new  \Symfony\Component\Console\Helper\Table($output);
        $table->setHeaders(['Getting the Magento root path ']);
        $table->setRows([
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getRoot()'],
            ['<bg=cyan>' . $this->directoryList->getRoot() . '</>'],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::ROOT</info>)'],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::ROOT) . '</>'],
        ]);
        $table->render();

        $table = new  \Symfony\Component\Console\Helper\Table($output);
        $table->setHeaders(['Getting the module path using ' . \Magento\Framework\Component\ComponentRegistrar::class]);
        $table->setRows([
            [\Magento\Framework\Component\ComponentRegistrar::class . '::getPath(<info>\Magento\Framework\Component\ComponentRegistrar::MODULE</info>, <info>' . self::MODULE_NAME . '</info>)'],
            [
                '<bg=cyan>' . $this->componentRegistrar->getPath(\Magento\Framework\Component\ComponentRegistrar::MODULE,
                    self::MODULE_NAME) . '</>',
            ],
        ]);
        $table->render();

        $table = new  \Symfony\Component\Console\Helper\Table($output);
        $table->setHeaders(['Getting module paths using ' . \Magento\Framework\Module\Dir::class . '::getDir(...)']);
        $table->setRows([
            [\Magento\Framework\Module\Dir::class . '::getDir(<info>' . self::MODULE_NAME . '</info>)'],
            ['<bg=cyan>' . $this->moduleDir->getDir(self::MODULE_NAME) . '</>'],
            new TableSeparator(),
            [\Magento\Framework\Module\Dir::class . '::getDir(<info>' . self::MODULE_NAME . '</info>,<info>\Magento\Framework\Module\Dir::MODULE_ETC_DIR</info>)',],
            [
                '<bg=cyan>' . $this->moduleDir->getDir(self::MODULE_NAME,
                    \Magento\Framework\Module\Dir::MODULE_ETC_DIR) . '</>',
            ],
            new TableSeparator(),
            [\Magento\Framework\Module\Dir::class . '::getDir(<info>' . self::MODULE_NAME . '</info>,<info>\Magento\Framework\Module\Dir::MODULE_I18N_DIR</info>)',],
            [
                '<bg=cyan>' . $this->moduleDir->getDir(self::MODULE_NAME,
                    \Magento\Framework\Module\Dir::MODULE_I18N_DIR) . '</>',
            ],
            new TableSeparator(),
            [\Magento\Framework\Module\Dir::class . '::getDir(<info>' . self::MODULE_NAME . '</info>,<info>\Magento\Framework\Module\Dir::MODULE_VIEW_DIR</info>)',],
            [
                '<bg=cyan>' . $this->moduleDir->getDir(self::MODULE_NAME,
                    \Magento\Framework\Module\Dir::MODULE_VIEW_DIR) . '</>',
            ],
            new TableSeparator(),
            [\Magento\Framework\Module\Dir::class . '::getDir(<info>' . self::MODULE_NAME . '</info>,<info>\Magento\Framework\Module\Dir::MODULE_CONTROLLER_DIR</info>)',],
            [
                '<bg=cyan>' . $this->moduleDir->getDir(self::MODULE_NAME,
                    \Magento\Framework\Module\Dir::MODULE_CONTROLLER_DIR) . '</>',
            ],
            new TableSeparator(),
            [\Magento\Framework\Module\Dir::class . '::getDir(<info>' . self::MODULE_NAME . '</info>,<info>\Magento\Framework\Module\Dir::MODULE_SETUP_DIR</info>)',],
            [
                '<bg=cyan>' . $this->moduleDir->getDir(self::MODULE_NAME,
                    \Magento\Framework\Module\Dir::MODULE_SETUP_DIR) . '</>',
            ],
        ]);
        $table->render();

        $table = new  \Symfony\Component\Console\Helper\Table($output);
        $table->setHeaders(['Getting Magento-specific paths using ' . \Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(...)']);
        $table->setRows([
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::APP)</info>'],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::APP) . '</>'],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::CACHE</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::CACHE) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::COMPOSER_HOME</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::COMPOSER_HOME) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::CONFIG</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::CONFIG) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::GENERATED</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::GENERATED) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::GENERATED_CODE</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::GENERATED_CODE) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::GENERATED_METADATA</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::GENERATED_METADATA) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::LIB_INTERNAL</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::LIB_INTERNAL) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::LIB_WEB</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::LIB_WEB) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::LOG</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::LOG) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::MEDIA</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::PUB</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::PUB) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::ROOT</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::ROOT) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::SESSION</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::SESSION) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::SETUP</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::SETUP) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::STATIC_VIEW</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::STATIC_VIEW) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::TMP</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::TMP) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::TMP_MATERIALIZATION_DIR</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::TMP_MATERIALIZATION_DIR) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::UPLOAD</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::UPLOAD) . '</>',],
            new TableSeparator(),
            [\Magento\Framework\App\Filesystem\DirectoryList::class . '::getPath(<info>\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR</info>)',],
            ['<bg=cyan>' . $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR) . '</>',],
        ]);
        $table->render();
    }
}
