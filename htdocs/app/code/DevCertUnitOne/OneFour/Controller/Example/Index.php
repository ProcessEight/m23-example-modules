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

namespace DevCertUnitOne\OneFour\Controller\Example;

/**
 * Serves /onefour/example/index
 */
class Index extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \DevCertUnitOne\OneFour\Model\Currency
     */
    private $currency;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \DevCertUnitOne\OneFour\Model\Currency     $currency
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \DevCertUnitOne\OneFour\Model\Currency $currency
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->currency          = $currency;
    }

    /**
     * Controller just outputs the <default> layout XML handle
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $remote = $this->getRequest()->getParam('remote');
        if ($remote == 1) {
            $rates = $this->currency->fetchRates();
            var_dump($rates);
        }

        return $this->resultPageFactory->create();
    }
}
