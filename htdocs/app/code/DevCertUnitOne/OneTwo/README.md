# DevCertUnitOne_OneTwo

## Purpose
Task for Certified Developer Study Group, Unit One, Task Two.

## Task
Product listing (grid) in the admin being initiated by a UIComponent inside of a div with the following binding:
`data-bind="scope:'product_listing.product_listing'"`

- Identify which file contains that div.

- Identify template file for mini-cart.

### Identify which file contains that div.
The file which contains the string is `vendor/magento/module-ui/view/base/ui_component/templates/listing/default.xhtml`.

The file was identified by:
- Open the product grid in the admin in a browser.
- Copy the string.
- Find it in the `view-source`.
- Look for any other identifying features, preferably on the same line
    - E.g. A class attribute
- Search the codebase for that identifying feature
- Verify it is the right template by adding a unique string (e.g. `abc123`) to some part of the template. 
- Clear caches, etc, then refresh the grid.
- Find the unique string in the `view-source`.

### Identify template file for mini-cart.
The template for the mini-cart is `htdocs/vendor/magento/module-checkout/view/frontend/templates/cart/minicart.phtml`.

It was identified using the same method as above.

