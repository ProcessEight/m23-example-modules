# DevCertUnitOne_OneFive

## Purpose
Task for Certified Developer Study Group, Unit One, Task Five.

## Task
Describe the difference in returned values for `ProductRepository` and `CustomerRepository` classes (`getList` and `getById` methods)

### Differences in the returned values of `\Magento\Catalog\Model\ProductRepository::getList` and `\Magento\Customer\Model\ResourceModel\CustomerRepository::getById` methods

`\Magento\Catalog\Model\ProductRepository::getList` returns an instance of `\Magento\Catalog\Api\Data\ProductSearchResultsInterface`.

`ProductSearchResultsInterface` exposes two methods: `setItems` and `getItems`. `getItems` returns an array of `\Magento\Catalog\Api\Data\ProductInterface`.

`\Magento\Customer\Model\ResourceModel\CustomerRepository::getById` returns an instance of `\Magento\Customer\Api\Data\CustomerInterface`. 

This instance _will_ contain detailed information about the customer instance (e.g. It will include extension attributes and custom attributes), whereas the instances returned by a call to `\Magento\Customer\Api\CustomerRepositoryInterface::getList` may not include this detailed attribute information (more information: https://devdocs.magento.com/codelinks/attributes.html).
