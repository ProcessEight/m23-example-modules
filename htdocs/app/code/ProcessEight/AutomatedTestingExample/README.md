# ProcessEight_AutomatedTestingExample

A library of common integration and unit tests.

* [Environment setup](#environment-setup)
    * [Configuring PHPUnit](#configuring-phpunit)
    * [Configuring PhpStorm](#configuring-phpstorm)
    * [Configure the database (for integration tests)](#configure-the-database-for-integration-tests)
* [Troubleshooting](#troubleshooting)
    * [Integration tests not behaving as expected](#integration-tests-not-behaving-as-expected)
* [Sources](#sources)

## Environment setup

### Versions

These tests were written against PHPUnit 6.5.14.

The tests were tested against Magento 2.3.1.

The PHP version was 7.2.17

### Configuring PHPUnit

Copy the `htdocs/app/code/ProcessEight/AutomatedTestingExample/dev/tests/integration/phpunit.processeight.xml` file to `htdocs/dev/tests/integration/phpunit.processeight.xml`.

### Configuring PhpStorm

#### Tell PhpStorm which version of PHPUnit it should use
1. Go to `Settings, Languages & Frameworks, PHP, Test Frameworks`
1. Add a new instance of PHPUnit.
1. Under `PHPUnit library`:
    1. Select `Use Composer autoloader`.
    1. In `Path to script: `, enter the path to the autoloader, e.g. `/var/www/html/project-name/htdocs/vendor/autoload.php`
1. The remaining settings can be left at their defaults.

#### Tell PhpStorm how to run the tests

These instructions are for running unit tests, but the configuration for unit tests is identical - just substitute unit for integration. 

1. Go to `Run`, `Edit Configurations`.
1. Create a new `PHPUnit` configuration with the following values:
    * Name: `ProcessEight AutomatedTestingExample Test Rig`
    * Test Runner:
        * Test Scope: `Defined in the configuration file`
        * Use alternative configuration file: `/path/to/magento/root/dev/tests/integration/phpunit.xml`
        * Test Runner options: `--testsuite "ProcessEight AutomatedTestingExample Tests"`
        
### Configure the database (for integration tests)

Copy the `htdocs/app/code/ProcessEight/AutomatedTestingExample/dev/tests/integration/etc/install-config-mysql.processeight.php` file to `htdocs/dev/tests/integration/etc/install-config-mysql.processeight.php` and update the database connection details accordingly.

There are more detailed notes on configuring the environment for integration tests in the Magento 2 DevDocs [[3]][3].

## Troubleshooting

### Integration tests not behaving as expected

Remember to clear the integration test cache if you've disabled the `TESTS_CLEANUP` environment variable:
```bash
$ rm -rf dev/tests/integration/tmp/sandbox-*
```

### Magento cannot create the database for integration tests

Magento will not create an empty database for you. This step must be done manually.

## Sources
* [Running Unit Tests in the CLI](http://devdocs.magento.com/guides/v2.1/test/unit/unit_test_execution_cli.html)
* [Running Unit Tests in PHPStorm](http://devdocs.magento.com/guides/v2.1/test/unit/unit_test_execution_phpstorm.html)

[1]: http://magento.stackexchange.com/questions/140314/magento-2-unit-test-with-mock-data-dont-work-why/140337#140337
[2]: http://devdocs.magento.com/guides/v2.1/test/unit/writing_testable_code.html
[3]: http://devdocs.magento.com/guides/v2.1/test/integration/integration_test_setup.html
[4]: http://devdocs.magento.com/guides/v2.1/install-gde/docker/docker-phpstorm-project.html
[5]: http://vinaikopp.com/2016/02/05/01_the_skeleton_module_kata/
[6]: http://vinaikopp.com/2016/02/05/02_the_plugin_config_kata/
[7]: http://vinaikopp.com/2016/02/22/03_the_around_interceptor_kata/
[8]: http://vinaikopp.com/2016/03/07/04_the_plugin_integration_test_kata/
[9]: http://vinaikopp.com/2016/03/21/05_the_route_config_kata/
[10]: http://vinaikopp.com/2016/04/04/06_the_action_controller_tdd_kata/
[11]: https://edmondscommerce.github.io/magento-2-controller-output-types/
[12]: http://magento-quickies.alanstorm.com/post/141260832260/magento-2-controller-result-objects
[13]: http://vinaikopp.com/2016/04/18/07_the_action_controller_integration_test_kata/
[14]: http://vinaikopp.com/2016/05/05/08_the_di_arguments_config_kata/