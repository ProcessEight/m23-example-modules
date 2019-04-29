# DevCertUnitOne\OneThree

## Purpose

To demonstrate how to create a custom config XML schema in Magento 2.

Tested on Magento 2.3.1.

## Installation

Install with composer.

## Execution

The module adds a new command which retrieves the custom config XML and displays it in the terminal.

## Overview

We will create a custom config XML file and have Magento validate them against our own custom XML schema. file where we can define our own custom config values.

Then we will demonstrate how to retrieve such values.

## In-depth explanation

To create a new, custom, config XML file, we need to create five files.

### The XSD file (`etc/filename.xsd`)

The first file to create is the XSD file. This file defines which elements are 
allowed to appear in our custom config XML file. Magento will use this file to 
validate our custom config XML.

### The XML file (`etc/filename.xml`)

This is the file where we will add our custom config XML. Magento treats this 
file just like any other config XML file. 

Both the XSD and it's matching XML file(s) must share the same name.

### Config/Converter.php

The purpose of this file is to create an array representation of 
the filename.xml file.

### Config/SchemaLocator.php

The purpose of this file is to tell Magento where to find the XSD 
to validate the custom config XML file.

### Config/WarehousesData.php

The purpose of this file is to extend `\Magento\Framework\Config\Data` 
and in so doing, give us a dependency which can be injected wherever 
we want to retrieve our custom config values.

### Wiring it all together

Firstly, we create a virtual type based on `\Magento\Framework\Config\Reader\Filesystem`.
We pass the two PHP classes, `\DevCertUnitOne\OneThree\Config\Converter` and 
`\DevCertUnitOne\OneThree\Config\SchemaLocator` as arguments to our virtual type
as well as a third string argument, which defines the name of our custom config XML file:
```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <!-- Add virtualType to configure our custom config reader -->
    <virtualType name="WarehousesDataConfigReader" type="Magento\Framework\Config\Reader\Filesystem">
        <arguments>
            <!-- Converts the config XML into an array -->
            <argument name="converter" xsi:type="object">DevCertUnitOne\OneThree\Config\Converter</argument>
            <!-- Tells Magento where to find the schema (XSD) for our custom config file -->
            <argument name="schemaLocator" xsi:type="object">DevCertUnitOne\OneThree\Config\SchemaLocator</argument>
            <!-- Defines the filename of our custom config XML file -->
            <argument name="fileName" xsi:type="string">warehouses_list.xml</argument>
        </arguments>
    </virtualType>
```

With our virtual type defined, we can now pass it as an argument to the third PHP class we created,
`\DevCertUnitOne\OneThree\Config\WarehousesData`.

```xml
    <!-- Inject this class wherever we need access to our custom config XML -->
    <type name="DevCertUnitOne\OneThree\Config\WarehousesData">
        <arguments>
            <!-- Virtual Type which validates and reads the custom config XML file -->
            <argument name="reader" xsi:type="object">WarehousesDataConfigReader</argument>
            <!-- Cache tag -->
            <argument name="cacheId" xsi:type="string">devcertunitone_warehouses_list_cache</argument>
        </arguments>
    </type>
```

Wherever we need to read our custom config XML file, we can inject the `WarehousesData` class:

```php

    /**
     * CreateCustomConfigXml constructor.
     *
     * @param \DevCertUnitOne\OneThree\Config\WarehousesData $configData
     */
    public function __construct(
        \DevCertUnitOne\OneThree\Config\WarehousesData $configData
    ) {
        $this->configData = $configData;

        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $warehouses = var_export($this->configData->get('warehouses_list'), true);
        $output->writeln("The custom config value is: {$warehouses}");
    }

```

## Digging deeper

### Using a virtual type as the Schema Locator

See `htdocs/vendor/magento/module-signifyd/etc/di.xml:64`.

This approach removes the necessity of creating a custom Schema Locator class.

## Sources

* [Magento 2.2 DevDocs: Configuration Interfaces](https://devdocs.magento.com/guides/v2.2/config-guide/config/config-files.html#config-files-classes-int)
* [Magento 2.2 DevDocs: Create or extend configuration types](https://devdocs.magento.com/guides/v2.2/config-guide/config/config-create.html)