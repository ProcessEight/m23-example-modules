# ProcessEight_ModuleManager

## Table of Contents

   * [ProcessEight_ModuleManager](#processeight_modulemanager)
      * [Add a new stage](#add-a-new-stage)
         * [__invoke](#__invoke)
         * [processStage](#processstage)
      * [Add a new pipeline](#add-a-new-pipeline)
         * [__invoke](#__invoke-1)
         * [processPipeline](#processpipeline)
      * [Add a new command](#add-a-new-command)

## Architectural philosophy

The architectural design of the Module Manager is built around the idea of a pipeline.

Each _command_ triggers the processing of a _pipeline_.

A Pipeline consists of a series of steps or _stages_.

The stage is the smallest unit of logic in the Module Manager. 

Stages and Pipelines should be designed in such a way that they can be chained with other Stages (so there should be no dependencies between Stages and Pipelines) and so that they can be re-used within other Pipelines.

The same applies to Pipelines - there should be no dependencies between Pipelines and they should also be able to be chained together with other Pipelines.

In this way new Pipelines can be created just by chaining existing Stages/Pipelines together and new Commands can be created by chaining existing Pipelines together. 

This should reduce the amount of time/boilerplate coding required to create new, useful Commands.

### Naming conventions

If a file is to be created which does not have a pre-defined name, then the user must be asked for their preferred name.

No 'magic' should be used to generate a name.

## Add a new stage
See `\ProcessEight\ModuleManager\Model\Stage\CreateCommandFolderStage` for a complete example.

Each stage should be responsible for the data it needs to work.

A Stage must have two methods:

### `__invoke`
Which is called whenever the pipeline is processed.

The `__invoke` method has one parameter, `array $payload`. The payload contains data which must be accessible in all Stages/Pipelines (e.g. The `is_valid` validation flag).

Values which need to be passed from stage to stage should be added to the payload array.

### `processStage`
This is where the business logic of the Stage is executed.

Stages can have as many extra public methods as necessary to capture all the data they need to do their job (e.g. For template variables or file paths).

## Add a new pipeline
See `\ProcessEight\ModuleManager\Model\Pipeline\CreateFolderPipeline` for a complete example.

The responsibility of the Pipeline class is to gather all the Stages/Pipelines needed for this Pipeline and inject them via DI; Also to invoke the processing of the Pipeline.

Pipelines must not have any logic of their own (e.g. The logic to create or validate a folder should be encapsulated in a Stage and then that added to a Pipeline).

A Pipeline must have two methods:

### `__invoke`
Just like a Stage. The `__invoke` method has one parameter, `array $payload`. The payload contains data which must be accessible in all Stages/Pipelines (e.g. The `is_valid` validation flag).

### `processPipeline`
Called from the `__invoke` method. This method defines and configures the Stages (or other Pipelines) in this Pipeline, then executes it.

## Add a new command
See `\ProcessEight\ModuleManager\Command\Module\BinMagentoCommandCommand` for a complete example

This class is a standard Symfony Console Component class. The purpose of this class is to take the input from the CLI, then pass it to the Pipeline/Stage class and execute the Pipeline.

The `processPipeline` method performs the same purpose as the `processPipeline` method in the Pipeline classes.

The `payload` argument should have at least one element, `is_valid`. Setting this to false in any Stage/Pipeline will cause execution of the remaining Stages/Pipelines to be skipped.

## Templates

Sometimes an artefact (e.g. Class, XML file, directory) needs to be generated. Templates and template variables are used to create these.

### Creating a template

Just save the target file into the `Template` folder, adding the `.template` suffix.

The contents of the `Template` folder are designed to reflect the structure of a module folder.

If an artefact can have a custom path (e.g. A Block class), then save the template in the top most folder, e.g. For Block classes this would be `Template\Block\{{BLOCK_CLASS_NAME}}.php.template`.

For artefacts which don't have a defined name (e.g. Most classes), then a template variable can be used as the filename. For a Block class, this could be `Template\Block\{{BLOCK_CLASS_NAME}}.php.template`. The `BLOCK_CLASS_NAME` variable must then be defined in a stage.

Template variable names are intended to be descriptive.

If they are taken from user input, then the class constant is used as the variable name, e.g:
```php
// \ProcessEight\ModuleManager\Command\Module\Add\BinMagentoCommandCommand::getTemplateVariables

        $templateVariables = array_merge($templateVariables, [
            '{{COMMAND_NAME}}'               => $input->getOption(ConfigKey::COMMAND_NAME),
            '{{COMMAND_DESCRIPTION}}'        => $input->getOption(ConfigKey::COMMAND_DESCRIPTION),
            '{{COMMAND_CLASS_NAME}}'         => $input->getOption(ConfigKey::COMMAND_CLASS_NAME),
            '{{COMMAND_CLASS_NAME_UCFIRST}}' => ucfirst($input->getOption(ConfigKey::COMMAND_CLASS_NAME)),
            '{{COMMAND_CLASS_NAME_STRTOLOWER}}' => strtolower($input->getOption(ConfigKey::COMMAND_CLASS_NAME)),
        ]);
```

If a template variable needs to be processed in some way (e.g. To make it upper or lower case), then this is achieved using PHP string manipulation methods. The name of the method used is appended to the template variable name (see example above).

## Convert a command

@todo: Make all these changes, then generate patch file, which can be used on multiple files (possibly) or at least a template for applying these changes

- Change the class to extend from BaseCommand
- Remove `moduleDir` dependency
- Inject `\Magento\Framework\App\Filesystem\DirectoryList`
- Pass `$pipeline` and `$directoryList` as arguments to `parent::__construct`
- Move any stages into a new pipeline
- Add `parent::configure()` to first line of `configure`
- Remove logic to get `\ProcessEight\ModuleManager\Model\ConfigKey::VENDOR_NAME` and `\ProcessEight\ModuleManager\Model\ConfigKey::MODULE_NAME` from `configure`
- Add `parent::execute($input, $output)` as first line of `execute` method
- Remove logic to set `\ProcessEight\ModuleManager\Model\ConfigKey::VENDOR_NAME` and `\ProcessEight\ModuleManager\Model\ConfigKey::MODULE_NAME` from `execute`
- Remove logic to `// Validate inputs` and `// Generate assets`
- Refactor bottom part of method thus:
```bash

@@ -149,31 +122,13 @@
             );
         }

-        // Validate inputs
-        $validationResult = $this->validateInputs([
-            ConfigKey::VENDOR_NAME       => $input->getOption(ConfigKey::VENDOR_NAME),
-            ConfigKey::MODULE_NAME       => $input->getOption(ConfigKey::MODULE_NAME),
-            ConfigKey::LAYOUT_XML_HANDLE => $input->getOption(ConfigKey::LAYOUT_XML_HANDLE),
-        ]);
-
-        if (!$validationResult['is_valid']) {
-            $output->writeln($validationResult['validation_message']);
+        $result = $this->processPipeline($input);

-            return 1;
-        }
-
-        // Generate assets
-        $creationResult = $this->generateModule([
-            ConfigKey::VENDOR_NAME       => $input->getOption(ConfigKey::VENDOR_NAME),
-            ConfigKey::MODULE_NAME       => $input->getOption(ConfigKey::MODULE_NAME),
-            ConfigKey::LAYOUT_XML_HANDLE => $input->getOption(ConfigKey::LAYOUT_XML_HANDLE),
-        ]);
-
-        foreach ($creationResult['creation_message'] as $message) {
+        foreach ($result['messages'] as $message) {
             $output->writeln($message);
         }

-        return $creationResult['is_valid'] ? 0 : 1;
+        return $result['is_valid'] ? 0 : 1;
     }

     /**
```
- Remove methods `validateInputs` and `generateAssets`
- Add methods `processPipeline` and `getTemplateVariables` and configure as appropriate
- Replace deprecated `getProcessedFilename` with `str_replace`

## To do

- [ ] Add some way to detect Magento version and generate code for that version
- [ ] Group messages by class name for easier debugging
- [ ] Refactor so that each stage is responsible for it's own data 
    - This will remove the setting of logic to the `config` array in the `*Command` classes which extend the Symfony command classes
    - This could involve defining the parameters for the `input` `options` in each stage and the command class just reads these
    - The command class could also set default values for commands based on ^above, so that questions needing identical answers (e.g. Defining VENDOR_NAME) aren't asked twice
- Refactor to make it simpler to pass context-sensitive data to each stage
    - Perhaps something like a context object/array?
    - Each stage defines the data it needs
    - The Command class somehow knows this and asks for it(?), possibly by reading the context class
- Perhaps we should start all over again and use traits instead of pipelines?
    - The traits could contain arrays of command options and define the template variables
    - A new command could be created by adding traits to a new command class
- Perhaps we should start all over again and use standalone classes injected into (or inherited by) the command class instead of stages and pipelines?
    - The standalone classes could contain arrays of command options and define the template variables
    - ...and also contain all logic needed to fulfil its responsibility (i.e. Create a folder)
    - A new command could be created by adding new standalone classes to a new command class (either by injecting or by extending the command class)