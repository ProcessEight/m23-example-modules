# M2StudyGroupUnit1_PluginsOrder

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

### What is the order of execution of multiple plugins applied to an object and it's parent?

The `sortOrder` property for plugins determine when their before, after, or around methods get called when more than one plugins are observing the same method.

The prioritization rules for ordering plugins:

- Before the execution of the observed method, Magento will execute plugins in ascending (from **lowest** to **greatest**) `sortOrder`.
    - During each plugin execution, Magento executes the current plugin’s before method.
    - After the before plugin completes execution, the current plugin’s around method will wrap and execute the next plugin or observed method.

- Following the execution of the observed method, Magento will execute plugins in descending (from **greatest** to **lowest**) `sortOrder`.
    - During each plugin execution, the current plugin will first finish executing its around method.
    - When the around method completes, the plugin executes its after method before moving on to the next plugin.

See also https://devdocs.magento.com/guides/v2.3/extension-dev-guide/plugins.html#prioritizing-plugins

Plugins defined in the global scope _will_ be executed even if Magento is currently executing in a more specific area (e.g. `frontend` or `backend`).

### Describe the limitations of a plugin (i.e. What type of data it CANNOT access and what type of classes cannot be customised with plugins). 

The first argument for the before, after, and around methods is an object that provides access to all public methods of the observed method’s class.

- Classes or methods marked `final` cannot be customised with plugins (because the plugin system relies on the Interception class being able to extend the target class).

- Only `public` methods can be customised with plugins.

- Static (class) methods. Plugins only work in a dynamic (object) context.

- `__construct`: The constructor is always the first method to be called (when the object is created), therefore nothing can be called before it.

- Virtual types: Virtual types never actually exist (other than in XML), so they cannot be intercepted.

- Objects that are instantiated before the interception class is bootstrapped