# ProcessEight_CheckoutOverrideKnockoutJsTemplateExample

## Purpose
Demonstrates how to override a KnockoutJS template on the frontend checkout in Magento 2

Tested on Magento Open Source 2.3.1.

## In detail

This example module replaces the default template for the checkout sidebar component (located in `/vendor/magento/module-checkout/view/frontend/web/template/sidebar.html`) with a custom template (located in `/app/code/ProcessEight/CheckoutOverrideKnockoutJsTemplateExample/view/frontend/web/template/checkout/sidebar.html`) which outputs a basic message.

The sidebar uiComponent outputs a `div` which displays items in the basket, order total and shipping information.

The component is defined in `/vendor/magento/module-checkout/view/frontend/layout/checkout_index_index.xml:339`:

```xml
...
<item name="sidebar" xsi:type="array">
    <item name="sortOrder" xsi:type="string">50</item>
    <item name="component" xsi:type="string">Magento_Checkout/js/view/sidebar</item>
    <item name="displayArea" xsi:type="string">sidebar</item>
    <item name="config" xsi:type="array">
        <item name="template" xsi:type="string">Magento_Checkout/sidebar</item>
        <item name="deps" xsi:type="array">
            <item name="0" xsi:type="string">checkout.steps</item>
        </item>
    </item>
    ...                                 
```

The `template` item node gives us a path of `Magento_Checkout/sidebar`, which translates to `/vendor/magento/module-checkout/view/frontend/web/template/sidebar.js`.

We can then use this path to override the file, creating a `requirejs-config.js` file in our module
`/app/code/ProcessEight/CheckoutOverrideKnockoutJsTemplateExample/view/frontend/requirejs-config.js`: 
```js
/**
 * This config file is merged with all the requirejs-config.js files of all the other modules
 * and then sent as one file to the browser
 */

var config = {
    map: {
        '*': {
            // Override the default Magento template with our own template
            // This is basically like using a Preference to completely replace a Magento file with our own
            'Magento_Checkout/template/sidebar.html': 'ProcessEight_CheckoutOverrideKnockoutJsTemplateExample/template/checkout/sidebar.html'
        }
    }
};
```
