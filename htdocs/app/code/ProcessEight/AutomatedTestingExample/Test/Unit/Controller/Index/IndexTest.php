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

namespace ProcessEight\AutomatedTestingExample\Controller\Index;

use ProcessEight\AutomatedTestingExample\Model\Exception\RequiredArgumentMissingException;
use Magento\Framework\App\Action\Context as ActionContext;
use Magento\Framework\Controller\Result\Raw as RawResult;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\HTTP\PhpEnvironment\Request;

class IndexTest extends \PHPUnit\Framework\TestCase
{
    /** @var \ProcessEight\AutomatedTestingExample\Controller\Index\Index */
    protected $controller;

    /** @var \Magento\Framework\Controller\Result\Raw|\PHPUnit_Framework_MockObject_MockObject */
    protected $mockRawResult;

    /** @var \Magento\Framework\HTTP\PhpEnvironment\Request|\PHPUnit_Framework_MockObject_MockObject */
    protected $mockRequest;

    /** @var \Magento\Framework\Controller\Result\Redirect|\PHPUnit_Framework_MockObject_MockObject */
    protected $mockRedirectResult;

    /**
     * Mock the objects required
     */
    protected function setUp()
    {
        // Mock the Result Factory
        /** @var \Magento\Framework\Controller\ResultFactory|\PHPUnit_Framework_MockObject_MockObject $mockRawResultFactory */
        $mockRawResultFactory = $this->getMockBuilder(ResultFactory::class)
                                     ->setMethods(['create'])
                                     ->disableOriginalConstructor()
                                     ->getMock();
        // Mock the Raw result object
        $this->mockRawResult = $this->getMockBuilder(RawResult::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();

        // Set our expectation (i.e. When we call ResultFactory::create(ResultFactory::TYPE_RAW) we expect to get a RawResult object back)
        $mockRawResultFactory->method('create')->with(ResultFactory::TYPE_RAW)->willReturn($this->mockRawResult);
        $mockRawResultFactory->method('create')->willReturn($this->mockRedirectResult);

        // Mock the ActionContext object
        /** @var \Magento\Framework\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject $mockContext */
        $mockContext = $this->getMockBuilder(ActionContext::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        // Mock the request
        $this->mockRequest = $this->getMockBuilder(Request::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $mockContext->method('getRequest')->willReturn($this->mockRequest);

        // Mock the objects required to redirect to the homepage
        $this->mockRedirectResult = $this->getMockBuilder(Redirect::class)
//		                                  ->setMethods( [ 'setUrl' ] )
                                         ->disableOriginalConstructor()
                                         ->getMock();

        $mockRedirectResultFactory = $this->getMockBuilder(RedirectFactory::class)
                                          ->setMethods(['create'])
                                          ->disableOriginalConstructor()
                                          ->getMock();
        $mockRedirectResultFactory->method('create')->willReturn($this->mockRedirectResult);

        $mockContext->method('getResultRedirectFactory')->willReturn($mockRedirectResultFactory);

        $this->controller = new \ProcessEight\AutomatedTestingExample\Controller\Index\Index(
            $mockContext,
            $mockRawResultFactory
        );
    }

    public function testReturnsResultInstance()
    {
        $this->mockRequest->method('getMethod')->willReturn('POST');
        $this->assertInstanceOf(ResultInterface::class, $this->controller->execute());
    }

    public function testReturns405MethodNotAllowedForNonPostRequests()
    {
        $this->mockRequest->method('getMethod')->willReturn('GET');
        $this->mockRawResult->expects($this->once())->method('setHttpResponseCode')->with(405);
        $this->controller->execute();
    }

    public function testReturns400BadRequestIfRequiredArgumentsAreMissing()
    {
        $incompleteArguments = [];
        $this->mockRequest->method('getMethod')->willReturn('POST');
        $this->mockRequest->method('getParams')->willReturn($incompleteArguments);

//        $this->mockUseCase->expects($this->once())->method('processData')->with($incompleteArguments)
//                          ->willThrowException(new RequiredArgumentMissingException('Test Exception: Required argument missing'));

        $this->mockRawResult->expects($this->once())->method('setHttpResponseCode')->with(400);

        $this->controller->execute();
    }

    public function testRedirectsToHomepageIfRequestWasValid()
    {
        $completeArguments = ['foo' => 123];
        $this->mockRequest->method('getMethod')->willReturn('POST');
        $this->mockRequest->method('getParams')->willReturn($completeArguments);

        $this->mockRedirectResult->expects($this->once())->method('setPath');

        $this->assertSame($this->mockRedirectResult, $this->controller->execute());
    }
}
