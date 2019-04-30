# DevCertUnitOne_OneFour

## Purpose
Task for Certified Developer Study Group, Unit One, Task Four.

## Task
Create a module with an action that depends on a URL parameter. If parameter: `remote` is set and equals to 1, an action will show a currency rate between dollar and pound. Currency rate should be obtained by a remote request in a separate class. Perform remote request in a constructor of the separate class.

How can you inject that separate class and avoid remote request when it is not needed?

- [x] Add frontend controller
- [x] Add frontend layout XML file
- [x] Add frontend template file
- [x] Add model to do remote request
- [x] Use Proxy in controller/block/whatever?
- [x] How does Magento handle requests to currency conversion services?
    - [x] Find example from core
    
### How can you inject that separate class and avoid remote request when it is not needed?

Using a Proxy class is the most obvious suggestion.

See https://devdocs.magento.com/guides/v2.3/extension-dev-guide/proxies.html