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
 * @category    m23-example-modules
 * @package     ModuleCreationTest.php
 * @copyright   Copyright (c) 2019 ProcessEight
 * @author      ProcessEight
 *
 */

namespace ProcessEight\AutomatedTestingExample\Test\Integration;

use Magento\Framework\Module\ModuleList;

class ModuleCreationTest extends \PHPUnit\Framework\TestCase
{
//    public function testNothing()
//    {
//        $this->markTestSkipped('If you can read this, then PhpStorm and PHPUnit are setup correctly');
//    }

    const MODULE_NAME = 'ProcessEight_AutomatedTestingExample';

    /**
     * Assert that Magento can detect the module
     */
    public function testTheModuleIsRegistered()
    {
        $registrar = new \Magento\Framework\Component\ComponentRegistrar();
        $this->assertArrayHasKey(
            self::MODULE_NAME,
            $registrar->getPaths(\Magento\Framework\Component\ComponentRegistrar::MODULE)
        );
    }

    /**
     * Assert that the module has been enabled
     */
    public function testTheModuleIsConfiguredAndEnabled()
    {
        /** @var \Magento\TestFramework\ObjectManager $objectManager */
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(\Magento\Framework\Module\ModuleList::class);

        $this->assertTrue($moduleList->has(self::MODULE_NAME), 'The module is not enabled');
    }
}
