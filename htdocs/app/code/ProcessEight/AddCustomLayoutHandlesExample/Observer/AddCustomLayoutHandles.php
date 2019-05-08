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

namespace ProcessEight\AddCustomLayoutHandlesExample\Observer;

/**
 * Class AddCustomLayoutHandles
 *
 * Demonstrates how to add custom layout handles to a page before the Layout XML is generated
 *
 * @package ProcessEight\AddCustomLayoutHandlesExample\Observer
 */
class AddCustomLayoutHandles implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Observes the 'layout_load_before' event, triggered in `\Magento\Framework\View\Layout\Builder::loadLayoutUpdates`
     * The data passed to this observer by the event is:
     * $observer->getData('full_action_name'):string
     * $observer->getData('layout'):\Magento\Framework\View\LayoutInterface
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Framework\View\LayoutInterface $layout */
        $layout = $observer->getData('layout');
        $layout->getUpdate()->addHandle('processeight_custom_handle');
    }
}
