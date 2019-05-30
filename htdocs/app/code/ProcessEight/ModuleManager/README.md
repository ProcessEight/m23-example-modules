# ProcessEight_ModuleManager

##Table of Contents

   * [ProcessEight_ModuleManager](#processeight_modulemanager)
      * [Add a new stage](#add-a-new-stage)
         * [__invoke](#__invoke)
         * [processStage](#processstage)
      * [Add a new pipeline](#add-a-new-pipeline)
         * [__invoke](#__invoke-1)
         * [processPipeline](#processpipeline)
      * [Add a new command](#add-a-new-command)

## Add a new stage
See `\ProcessEight\ModuleManager\Model\Stage\CreateFolderStage` for a complete example.

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

A Pipeline must have four methods:

### `__invoke`
Just like a Stage. The `__invoke` method has one parameter, `array $payload`. The payload contains data which must be accessible in all Stages/Pipelines (e.g. The `is_valid` validation flag).

### `processPipeline`
Called from the `__invoke` method. This method defines and configures the Stages (or other Pipelines) in this Pipeline, then executes it.

Just like a Stage, Pipelines have a getter and setter method, which the Pipeline uses to configure the Stages and any other Pipelines in this Pipeline:

## Add a new command
See `\ProcessEight\ModuleManager\Command\Module\BinMagentoCommandCommand` for a complete example

This class is a standard Symfony Console Component class. The purpose of this class is to take the input from the CLI, then pass it to the Pipeline/Stage class and execute the Pipeline.

The `processPipeline` method performs the same purpose as the `processPipeline` method in the Pipeline classes.

The `payload` argument should have at least one element, `is_valid`. Setting this to false in any Stage/Pipeline will cause execution of the remaining Stages/Pipelines to be skipped.
