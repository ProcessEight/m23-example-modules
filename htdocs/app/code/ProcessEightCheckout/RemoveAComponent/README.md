# ProcessEightCheckout_RemoveAComponent

## Purpose

This module demonstrates how to remove a component from the checkout.

The module removes the `discount` UI Component, which in turn prevents the `Apply Discount` box from appearing at the payment stage.

Tested on Magento Open Source 2.3.1.

## Explanation

To remove a component, add an after plugin on `\Magento\Checkout\Block\Checkout\LayoutProcessor::process` and reference the component in the nested array (generated from the `checkout_index_index.xml` file):

```php
public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $processor, $jsLayout)
{
    unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['discount']);

    return $jsLayout;
}
```

If you remove a component, it is removed and not loaded. If you disable a component, it is loaded but not rendered. 
