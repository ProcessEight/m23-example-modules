# DevCertUnitOne_OneSix

## Purpose
Task for Certified Developer Study Group, Unit One, Task Six.

## Task

Create a module with a custom class extending `Magento\Framework\App\Action\Action`
Implement the `dispatch` method (which will call its parent). 

Create 6 plugins for `Action\Action::dispatch` with the following `sortOrders`:

- before - 11, 
- around - 12, 
- after - 13, 
- before - 7, 
- around - 9, 
- after -  10

...and 6 plugins for your actions' `dispatch` method:

- before - 4, 
- around - 5, 
- after - 6, 
- before - 1, 
- around - 2, 
- after - 3

For the around plugin with a `sortOrder` of 2, create another before plugin.

Modify the parents' `dispatch` method call to pass a string parameter, `test`.

Each plugin should log to a file its name, as well as the method's parameters, if applicable.

What is the order of execution of multiple plugins applied to an object and it's parent?

Describe the limitations of a plugin (i.e. What type of data it CANNOT access and what type of classes cannot be customised with plugins). 

Disable the module after you finish.

### Hints

- [x] Create a class which extends `\Magento\Framework\App\Action\Action`
- [x] Override the `\Magento\Framework\App\Action\Action::dispatch` method
- [x] Create a plugin class
- [x] Add Logger as dependency to plugin class
- [x] Log method name, parameters to log file
- [ ] Pass string parameter `test` to parent `dispatch` method
- [ ] Disable the module after you finish.

### What is the order of execution of multiple plugins applied to an object and it's parent?

Firstly, plugins with increasing sort order are called in **ascending** order. So three plugins (of any type) with respective sort orders of 10, 30 and 20 will be executed in the order 10, 20, 30. 

Secondly, the original 'intercepted' method is then called.

Finally, plugins with decreasing sort order are called in **descending** order. So three plugins (of any type) with respective sort orders of 40, 60 and 50 will be executed in the order 60, 50, 40.

`around` plugins wrap the intercepted method, executing both before and after it. Where there is more than one `around` plugin defined for a method, `around` plugins wrap any subsequently defined plugins.

Refer to the DevDocs for a more in-depth example: https://devdocs.magento.com/guides/v2.3/extension-dev-guide/plugins.html#prioritizing-plugins

### Describe the limitations of a plugin (i.e. What type of data it CANNOT access and what type of classes cannot be customised with plugins). 

The first argument for the before, after, and around methods is an object that provides access to all public methods of the observed method’s class.

- Classes or methods marked `final` cannot be customised with plugins (because the plugin system relies on the Interception class being able to extend the target class).

- Only `public` methods can be customised with plugins.

- Static (class) methods. Plugins only work in a dynamic (object) context.

- `__construct`: The constructor is always the first method to be called (when the object is created), therefore nothing can be called before it.

- Virtual types: Virtual types never actually exist (other than in XML), so they cannot be intercepted.

- Objects that are instantiated before the interception class is bootstrapped
