<?php
/**
 * ProcessEightCheckout
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEightCheckout for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 ProcessEightCheckout
 * @author      ProcessEightCheckout
 *
 */

declare(strict_types=1);

namespace ProcessEightCheckout\RemoveAComponent\Plugin\Block\Checkout;

class LayoutProcessorPlugin
{
    /**
     * Checkout LayoutProcessor after process plugin.
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $processor
     * @param array                                            $jsLayout
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $processor, $jsLayout)
    {
        unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['discount']);

        return $jsLayout;
    }
}
