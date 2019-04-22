# DevCertUnitOne_OneOne

## Abstract
Task for Certified Developer Study Group, Unit One, Task One.

## Task
Create a module that provides a custom implementation for a `Magento\Vault\Api\PaymentTokenManagementInterface`, and also adds a plugin to the method `Magento\Dhl\Model\Carrier::setRequest`. The plugin logs into the file fields being set to the Dhl request.
Log file should be located in your modules' `var` folder.

- How do you address those dependencies from the native modules in your code?
- Describe different ways to obtain a path to the module's folder and compare them.

### Hints
* Use virtual types and DI.xml to create the log

- Add a preference for the interface
- Add an `after` plugin for `Magento\Dhl\Model\Carrier::setRequest`
- Create the `var` folder
- Use virtual types and DI.xml to create the logger for the plugin
    - Find an example from core, or a third party module

### Addressing dependencies from native (core?) modules in client code

### Different ways to obtain the path to a module folder