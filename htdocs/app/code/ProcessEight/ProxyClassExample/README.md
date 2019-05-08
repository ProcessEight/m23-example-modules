# ProcessEight_ProxyClassExample

## Purpose
Demonstration of how to use Proxy classes in Magento 2.

Tested on Magento Open Source 2.3.1.

## Run it
Run the following command.
```bash
$ php72 -f bin/magento process-eight:example:proxy-classes:without-proxy
You should have experienced a ten second delay before seeing this message.
That's because this command defines \ProcessEight\ProxyClassExample\Model\FastLoading as a dependency, but FastLoading defines \ProcessEight\ProxyClassExample\Model\SlowLoading as a dependency.
So the five-second penalty of instantiating SlowLoading is incurred even if that dependency isn't actually used in this request, command, etc.
The solution to this is to use Proxy Classes.
Try running the process-eight:example:proxy-classes:with-proxy command. It should be much quicker.
NOTE:  If you run the process-eight:example:proxy-classes:with-proxy command without disabling the process-eight:example:proxy-classes:without-proxy command, you won't notice any performance difference. This is because the Symfony Console Component initialises all commands as part of it's bootstrapping procedure, and thus loads the SlowLoading class as part of the initialisation of the process-eight:example:proxy-classes:without-proxy command. To avoid this, you will need to comment out the process-eight:example:proxy-classes:without-proxy command in di.xml, clear cache and then run the process-eight:example:proxy-classes:with-proxy command.
```
After following the instructions above:
```bash
$ php72 -f bin/magento process-eight:example:proxy-classes:with-proxy
You should NOT have experienced a ten second delay before seeing this message.
NOTE:  If you DID experience a ten-second delay, then try disabling the process-eight:example:proxy-classes:without-proxy command, otherwise you won't notice any performance difference. This is because the Symfony Console Component initialises all commands as part of it's bootstrapping procedure, and thus loads the SlowLoading class as part of the initialisation of the process-eight:example:proxy-classes:without-proxy command. To avoid this, you will need to comment out the process-eight:example:proxy-classes:without-proxy command in di.xml, clear cache and then run the process-eight:example:proxy-classes:with-proxy command.
```

## Explanation

This module demonstrates how to use Proxy Classes in order to avoid the performance hit of loading resource-intensive dependencies of injected classes.

## More information

Refer to the Dev Docs: 

https://devdocs.magento.com/guides/v2.3/extension-dev-guide/proxies.html
