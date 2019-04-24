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

declare(strict_types=1);

namespace ProcessEight\AutomatedTestingExample\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\Controller\Result\RedirectFactory */
    protected $resultRedirectFactory;

    /** @var \Magento\Framework\Controller\Result\Raw */
    protected $result;

    /** @var \Magento\Framework\Controller\ResultFactory */
    protected $resultFactory;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context       $context
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory
    ) {
        parent::__construct($context);
        $this->resultFactory         = $resultFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * If the request is not a POST, return a method not allowed result.
     * Otherwise, process the request and redirect to the homepage.
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return !$this->isPostRequest() ? $this->getMethodNotAllowedResult() : $this->processRequestAndRedirect();
    }

    /**
     * Is this a POST request?
     *
     * @return bool
     */
    protected function isPostRequest() : bool
    {
        return ($this->getRequest()->getMethod() === 'POST');
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\ResultInterface
     */
    protected function getMethodNotAllowedResult()
    {
        $this->result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
        $this->result->setHttpResponseCode(405);

        return $this->result;
    }

    /**
     * Process the request and redirect to the homepage if successful.
     * If not, return a bad request response code.
     *
     * @return \Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\ResultInterface
     */
    protected function processRequestAndRedirect()
    {
        try {
            // Process the data. This could involve adding it to an array or Data Transfer Object.
            $params[] = $this->getRequest()->getParams();

            // Now redirect to the homepage
            $redirect = $this->resultRedirectFactory->create();
            $redirect->setPath('/');

            return $redirect;
        } catch (\ProcessEight\AutomatedTestingExample\Model\Exception\RequiredArgumentMissingException $exception) {
            return $this->getBadRequestResult();
        }
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\ResultInterface
     */
    protected function getBadRequestResult()
    {
        $this->result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
        $this->result->setHttpResponseCode(400);

        return $this->result;
    }

}
