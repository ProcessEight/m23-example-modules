# ProcessEight_ExtensionAttributesExample

## Purpose
Demonstration of how to use Extension Attributes in Magento 2.

Tested on Magento Open Source 2.3.1.

- [x] Add example of using EA with scalar type 
    - See `ProcessEight_AddExtensionAttributeCustomerExample`
- [x] Add example of using EA with array type
    - See how `website_ids` are added to the Product entity
- [x] Add example of using EA with object type
- [x] Add example of using EA with object type and join
- [ ] Add command to generate `extension_attributes.xml`

## Run it
Run the following command.
```bash
$ /usr/bin/php7.2 -f bin/magento process-eight:example:extension-attributes
SKU: 24-MB01
ID: 1
Type: object
processeight_stock_item: Array
(
    [qty] => 200.0000
    [item_id] => 1
)

Type: array
category_links: Array
(
    [0] => Array
        (
            [category_id] => 3
            [position] => 0
        )

    [1] => Array
        (
            [category_id] => 4
            [position] => 0
        )

)

Type: array
website_ids: Array
(
    [0] => 1
)

Type: array
external_links: Array
(
    [0] => ProcessEight\ExtensionAttributesExample\Model\ExternalLink Object
        (
            [link:ProcessEight\ExtensionAttributesExample\Model\ExternalLink:private] => https://duckduckgo.com/
            [linkId:ProcessEight\ExtensionAttributesExample\Model\ExternalLink:private] => 1
            [productId:ProcessEight\ExtensionAttributesExample\Model\ExternalLink:private] => 1
            [linkType:ProcessEight\ExtensionAttributesExample\Model\ExternalLink:private] => Search Engine
            [extensionAttributes:ProcessEight\ExtensionAttributesExample\Model\ExternalLink:private] => 
        )

)

All done!
```

## Explanation

This module defines two different Extension Attributes.

The first example demonstrates how to add a new attribute with an object as the value, to the Product entity.

The second example demonstrates how to add a new attribute with an object as the value, whose own attributes are initialised with specific values using the Extension Attribute `join` mechanism, to the Product entity.

## More information

Anatomy of Magento 2: Extension Attributes

https://gist.github.com/ProcessEight/da06d767a400a62d76faaa791ed3d0ca

Refer to the Dev Docs: 

https://devdocs.magento.com/guides/v2.3/extension-dev-guide/attributes.html

Stack Overflow: How do the extension attributes work in Magento 2?

https://magento.stackexchange.com/q/87452

Gist: Saving an EAV Product attribute via the extensions mechanism

https://gist.github.com/nevvermind/155952b0b01773f4b42f
