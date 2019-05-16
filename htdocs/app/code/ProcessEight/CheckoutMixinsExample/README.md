# ProcessEight_CheckoutMixinsExample

## Purpose
Demonstrates how to configure a Mixin to override one or more functions in a uiComponent.

Tested on Magento Open Source 2.3.1.

## In detail

This example module 'extends' the `Magento_Checkout/js/model/shipping-save-processor/default::saveShippingInformation()` function.

The 'extended' function then outputs a message to the browser console, then calls the original function.
