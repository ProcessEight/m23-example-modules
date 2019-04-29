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

namespace ProcessEight\AddCustomAdminhtmlRouterExample\Controller\Adminhtml\Example;

/**
 * Serves /admin/addcustomadminhtmlrouterexample/example/index
 */
class Index extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @see app/code/ProcessEight/AddCustomAdminhtmlRouterExample/etc/acl.xml
     */
    const ADMIN_RESOURCE = 'ProcessEight_AddCustomAdminhtmlRouterExample::index';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        // Highlight the 'Process Eight Extensions' menu
        $resultPage->setActiveMenu('ProcessEight_Shared::extensions');
        // Set the title (on the page and in the browser title bar)
        $resultPage->getConfig()->getTitle()->prepend(__('Process Eight Add Custom Adminhtml Router Example'));
        return $resultPage;
    }
}
