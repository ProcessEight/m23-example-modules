# ProcessEight_ExtensionAttributesExample

## Purpose
Demonstration of how to use Extension Attributes in Magento 2.

Tested on Magento Open Source 2.3.1.

- Add example of using EA with scalar type
- Add example of using EA with array type
- Add example of using EA with object type
- Add example of using EA with object type and join

## Run it
Run the following command.
```bash
$ /usr/bin/php7.2 -f bin/magento process-eight:custom-logger-example
Hopefully, something should've been written to /var/log/custom_logger_example.log
```
The command should produce something like this:
```bash
$ ls -laht var/log/total 16K
drwxrwxr-x 2 zone8 zone8 4.0K Apr 27 10:25 .
-rw-rw-r-- 1 zone8 zone8  318 Apr 27 10:25 custom_logger_example.log
-rw-rw-r-- 1 zone8 zone8  130 Apr 27 10:25 debug.log
drwxrwxr-x 8 zone8 zone8 4.0K Apr 27 10:23 ..
$ cat var/log/custom_logger_example_0908.log 
[2019-04-27 09:25:58] customLoggerExample.DEBUG: DEF456 This is a message written by ProcessEight\CustomLoggerExample\Logger\Logger at 20190427 09:25:58 [] []
[2019-04-27 09:25:58] customLoggerExample.DEBUG: DEF456 This is a message written by ProcessEight\CustomLoggerExample\Logger\Logger at 20190427 09:25:58 [] []
```

## Explanation

This module demonstrates how to create a custom log file by extending Monolog, the default logging system in Magento 2.

Two examples are provided: The first defines and configures a new logger by creating new classes which extend from Magento's own base Logger implementation. 

The second example uses `virtualType`s to configure a new logger which uses Magento's base Logger classes as their base type. 

Both examples do exactly the same thing and produce the same output.

### First example

#### \ProcessEight\CustomLoggerExample\Logger\Handler

The `Handler` class defines where the log file is written and the adapter used to write it. 

In the `virtualType` example, a new virtual class is created, which is then configured in the `di.xml`:
```xml
<!--
/**
 * Example using virtual types.
 * Useful when the logic of the logging classes is not modified
 */
 -->
<!-- Configure virtual type for the logger -->
<virtualType name="ProcessEight_CustomLoggerExample_Debug" type="Magento\Framework\Logger\Handler\Base">
    <arguments>
        <!-- By default, log files will be created in the Magento base folder -->
        <!-- Or, you can provide a path relative to the Magento base folder -->
        <argument name="fileName" xsi:type="string">/var/log/custom_logger_example_virtual_type.log</argument>
    </arguments>
</virtualType>
```

#### Defining a new log file name

Modify the `fileName` argument of the `Handler` type:
```xml
<type name="ProcessEight\CustomLoggerExample\Logger\Handler">
    <arguments>
        <!-- By default, log files will be created in the Magento base folder -->
        <!-- Or, you can provide a path relative to the Magento base folder -->
        <argument name="fileName" xsi:type="string">/var/log/custom_logger_example.log</argument>
    </arguments>
</type>
```
The `di.xml` modifies the properties of the `\ProcessEight\CustomLoggerExample\Logger\Handler` class:
```php
<?php
namespace ProcessEight\CustomLoggerExample\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Log file name
     * @var string
     */
    protected $fileName = '/var/log/custom_logger_example.log';
}
```

## More information

Refer to the Dev Docs: 

https://devdocs.magento.com/guides/v2.3/config-guide/log/log-magento.html

Logging database activity: 

https://devdocs.magento.com/guides/v2.3/config-guide/log/log-db.html