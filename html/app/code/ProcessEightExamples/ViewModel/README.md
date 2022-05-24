# ProcessEightExamples_ViewModel

## Purpose

An example of how to use a View Model.

## Instructions

1. Create a View Model class. The ViewModel class can be located anywhere in the module.

```injectablephp
<?php

declare(strict_types=1);

namespace ProcessEightExamples\ViewModel\ViewModel;

class StoreConfig implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

}
```

2. Inject the class into the chosen block class in Layout XML:

```xml
<block ...>
    <arguments>
        <argument name="px8_examples_view_model" xsi:type="object">ProcessEightExamples\ViewModel\ViewModel\StoreConfig</argument>
    </arguments>
</block>
```

3. Access the View Model in the block class's template using `getData`:

```injectablephp
/** @var \ProcessEightExamples\ViewModel\ViewModel\StoreConfig $storeConfigViewModel */
$storeConfigViewModel = $block->getData('px8_examples_view_model');

if ($storeConfigViewModel->shouldRedirectToCart()) : 
// Do something with this information
endif;
```

## How it works

The template calls a method in the view model which retrieves a config value.

Go to any product page and the template displays a message from the view model.

## Notes

The Argument name must be unique in the scope of the block, i.e. If there are two arguments, the last defined argument replaces the first one.

Tested on Magento Open Source 2.3.5.
