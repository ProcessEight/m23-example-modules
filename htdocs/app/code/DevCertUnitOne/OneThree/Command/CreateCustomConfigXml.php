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

namespace DevCertUnitOne\OneThree\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateCustomConfigXml
 *
 * Demonstrates how to create and read values from a custom config XML file
 */
class CreateCustomConfigXml extends Command
{
    /**
     * @var \DevCertUnitOne\OneThree\Config\WarehousesData $configData
     */
    private $configData;

    /**
     * CreateCustomConfigXml constructor.
     *
     * @param \DevCertUnitOne\OneThree\Config\WarehousesData $configData
     */
    public function __construct(
        \DevCertUnitOne\OneThree\Config\WarehousesData $configData
    ) {
        $this->configData = $configData;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("devcertunitone:onethree:custom-config-xml");
        $this->setDescription("Demonstrates how to create and read values from a custom config XML file");

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $warehouses = var_export($this->configData->get('warehouses_list'), true);
        $output->writeln("The custom config value is: {$warehouses}");
    }
}
