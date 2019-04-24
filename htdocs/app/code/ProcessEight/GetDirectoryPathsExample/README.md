# ProcessEight_GetDirectoryPathsExample

## Abstract
Demonstrates how to programmatically retrieve paths to several types of Magento-specific directory (e.g. `base`, `media`, `var`, etc).

## In detail

Three methods of programmatically retrieving directory paths are demonstrated in this module:

- Using `\Magento\Framework\Module\Dir::getDir`, which returns the absolute path to the module folder, or a specific type of module subdirectory (`etc`, `i18n`, `controllers`, `view`), depending on the arguments passed.

- Using `\Magento\Framework\App\Filesystem\DirectoryList::getPath`, which returns a specific type of Magento folder.

- Using `\Magento\Framework\Component\ComponentRegistrar::getPath`, which returns the path to a specific module folder.
