# ProcessEightCheckout_DisableAComponent

## Purpose

This module demonstrates how to disable a UI Component in the checkout.

The module disables the `discount` UI Component, which in turn prevents the `Apply Discount` box from appearing at the payment stage.

Tested on Magento Open Source 2.3.1.

## Explanation

To disable a component, create a `checkout_index_index.xml` layout file and add the following instructions:

```xml
<item name="discount" xsi:type="array">
    <item name="config" xsi:type="array">
        <item name="componentDisabled" xsi:type="boolean">true</item>
    </item>
</item>
```

If you disable a component, it is loaded but not rendered. If you remove a component, it is removed and not loaded.
