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
 * @package     ProcessEight_AutomatedTestingExample
 * @copyright   Copyright (c) 2019 ProcessEight
 * @author      ProcessEight
 *
 */

namespace ProcessEight\AutomatedTestingExample\Test\Integration;

use Magento\Framework\App\Route\ConfigInterface as RouteConfigInterface;
use Magento\Framework\App\Router\Base as BaseRouter;
use Magento\TestFramework\Request;

class RouteConfigTest extends \PHPUnit\Framework\TestCase
{
	/** @var \Magento\TestFramework\ObjectManager */
	protected $objectManager;

	protected function setUp()
	{
		$this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
	}

	/**
	 * @magentoAppArea frontend
	 */
	public function testRouteIsConfigured()
	{
		/** @var \Magento\Framework\App\Route\ConfigInterface $routeConfig */
		$routeConfig = $this->objectManager->create( RouteConfigInterface::class );
		$this->assertContains(
		    'ProcessEight_AutomatedTestingExample',
            $routeConfig->getModulesByFrontName( 'processeight' )
        );
	}

	/**
	 * @magentoAppArea frontend
	 */
	public function testProcessEightIndexIndexActionControllerIsFound()
	{
		// Mock the request object
		/** @var \Magento\TestFramework\Request $request */
		$request = $this->objectManager->create( Request::class );
		$request->setModuleName( 'processeight' )
		        ->setControllerName( 'index' )
		        ->setActionName( 'index' );

		// Ask the BaseRouter class to match our mock request to our controller action class
		/** @var \Magento\Framework\App\Router\Base $baseRouter */
		$baseRouter     = $this->objectManager->create( BaseRouter::class );
		$expectedAction = \ProcessEight\AutomatedTestingExample\Controller\Index\Index::class;
		$this->assertInstanceOf( $expectedAction, $baseRouter->match( $request ) );
	}
}

