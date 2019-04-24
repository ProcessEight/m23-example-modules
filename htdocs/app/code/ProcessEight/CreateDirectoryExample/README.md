# ProcessEight_CreateDirectoryExample

## Abstract
Demonstrates how to programmatically create folders in Magento 2

## In detail

Three methods of programmatically creating folders are demonstrated in this module:

- Using `\Magento\Framework\Module\Dir::getDir`, which returns the absolute path to the module folder, or a specific type of module subdirectory (`etc`, `i18n`, `controllers`, `view`), depending on the arguments passed.
