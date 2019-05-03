<?php

namespace M2StudyGroupUnit1\PluginsOrder\Controller\Index;

use Magento\Framework\App\RequestInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Psr\Log\LoggerInterface                   $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->logger = $logger;
    }

    /**
     * @param RequestInterface $request
     * @param string           $test
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function dispatch(RequestInterface $request, $test = 'parent dispatch')
    {
        $this->logger->info('Module Action Dispatch method is called', [$request, $test]);

        return parent::dispatch($request);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
