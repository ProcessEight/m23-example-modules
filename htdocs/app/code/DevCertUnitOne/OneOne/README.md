# DevCertUnitOne_OneOne

## Purpose
Task for Certified Developer Study Group, Unit One, Task One.

## Task
Create a module that provides a custom implementation for a `Magento\Vault\Api\PaymentTokenManagementInterface`, and also adds a plugin to the method `Magento\Dhl\Model\Carrier::setRequest`. The plugin logs into the file fields being set to the Dhl request.
Log file should be located in your modules' `var` folder.

- How do you address those dependencies from the native modules in your code?
- Describe different ways to obtain a path to the module's folder and compare them.

### Hints
* Use virtual types and DI.xml to create the log

- [x] Add a preference for the interface
- [x] Add an `after` plugin for `Magento\Dhl\Model\Carrier::setRequest`
- [x] Create the `var` folder programmatically
- [x] Use virtual types and DI.xml to create the logger for the plugin
    - Find an example from core, or a third party module
- [ ] Log the request params

### Addressing dependencies from native modules in client code
- Dependencies are explicitly stated in `composer.json` and `module.xml`
- Dependencies are injected into the classes using Dependency Injection
- Virtual types are used to create and configure a new logger class which extends from the native logger class without explicitly declaring and extending it in PHP code

### Describe different ways to obtain the path to a module folder

The method I chose was to use `\Magento\Framework\Module\Dir::getDir`, which returns the absolute path to the module folder.

By passing a secondary argument to `\Magento\Framework\Module\Dir::getDir`, a specific type of module subdirectory (`etc`, `i18n`, `controllers`, `view`) can be returned.

An alternative is to use `\Magento\Framework\App\Filesystem\DirectoryList`.