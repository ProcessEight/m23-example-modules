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

namespace ProcessEight\ViewOnFrontend\Block\Adminhtml\Product\Edit\Button;

/**
 * Class OpenOnFrontend
 *
 * Defines settings used to create button
 *
 * @package ProcessEight\ViewOnFrontend\Block\Adminhtml\Product\Edit\Button
 */
class OpenOnFrontend extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic
{
    /**
     * @var \Magento\Catalog\Model\Product\Url
     */
    private $productUrl;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * OpenOnFrontend constructor.
     *
     * @param \Magento\Framework\View\Element\UiComponent\Context $context
     * @param \Magento\Framework\Registry                         $registry
     * @param \Magento\Catalog\Model\Product\Url                  $productUrl
     * @param \Magento\Store\Model\StoreManagerInterface          $storeManager
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product\Url $productUrl,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context, $registry);

        $this->productUrl   = $productUrl;
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label'      => __('Open In Default Store View'),
            'class'      => 'action-secondary',
            'on_click'   => "window.open('" . $this->getSeoUrl() . "', '_blank')",
            'sort_order' => 10,
        ];
    }

    /**
     * Get frontend SEO URL for product in default store view
     *
     * @return string
     */
    private function getSeoUrl() : string
    {
        $storeId = $this->storeManager->getDefaultStoreView()->getId();
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->getProduct();
        $product->setStoreId($storeId);

        return $this->productUrl->getProductUrl($product);
    }
}
