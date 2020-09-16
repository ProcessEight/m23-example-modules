<?php
/**
 * ProcessEight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @copyright   Copyright (c) 2020 ProcessEight
 * @author      ProcessEight
 *
 */

namespace ProcessEight\ModuleManager\Model;

/**
 * Interface ConfigKey
 *
 * @todo    Refactor to move these constants into the appropriate stages
 *
 * @package ProcessEight\ModuleManager\Model
 */
interface ConfigKey
{
    /**
     * Vendor name
     */
    const VENDOR_NAME = 'vendor-name';

    /**
     * Module name
     */
    const MODULE_NAME = 'module-name';

    /**
     * Absolute path to module
     */
    const MODULE_FOLDER_PATH = 'module-folder-path';

    /**
     * Absolute path to etc folder
     */
    const ETC_FOLDER_PATH = 'etc-folder-path';

    /**
     * Front name of controller, used to generate routes.xml
     */
    const FRONT_NAME = 'front-name';

    /**
     * Controller directory name, used to create controller sub-directory
     */
    const CONTROLLER_DIRECTORY_NAME = 'controller-directory-name';

    /**
     * Controller action class name, used to generate Controller Action Class name
     */
    const CONTROLLER_ACTION_NAME = 'controller-action-name';

    /**
     * Layout XML handle, used to generate Layout XML filename
     */
    const LAYOUT_XML_HANDLE = 'layout-xml-handle';

    /**
     * Template name, used to generate the PHTML file name
     */
    const TEMPLATE_NAME = 'template-name';

    /**
     * Block directory name
     */
    const BLOCK_DIRECTORY_NAME = 'block-directory-name';

    /**
     * Block class name
     */
    const BLOCK_CLASS_NAME = 'block-class-name';

    /**
     * Command name, used to invoke the command on the CLI
     */
    const COMMAND_NAME = 'command-name';

    /**
     * Brief description of what the command does
     */
    const COMMAND_DESCRIPTION = 'command-description';

    /**
     * Command class name
     */
    const COMMAND_CLASS_NAME = 'command-class-name';

    /**
     * Method to intercept (in format \Vendor\Namespace\Path\To\Class::methodToIntercept)
     */
    const METHOD_TO_INTERCEPT_NAMESPACE = 'method-to-intercept-namespace';

    /**
     * Type of plugin to create
     */
    const PLUGIN_TYPE = 'plugin-type';

    /**
     * Magento config area where plugin will be created
     */
    const PLUGIN_AREA = 'plugin-area';

    /**
     * Plugin directory path
     */
    const PLUGIN_DIRECTORY_PATH = 'plugin-directory-path';

    /**
     * The name of the plugin class
     */
    const PLUGIN_CLASS_NAME = 'plugin-class-name';

    /**
     * The class containing the method we are intercepting / the 'subject' class
     */
    const INTERCEPTED_CLASS_NAMESPACE = 'intercepted-class-namespace';

    /**
     * The method we are intercepting
     */
    const INTERCEPTED_METHOD_NAME = 'intercepted-method-name';

    /**
     * Path to observer class
     */
    const OBSERVER_DIRECTORY_PATH = 'observer-directory-path';

    /**
     * Observer class name
     */
    const OBSERVER_CLASS_NAME = 'observer-class-name';

    /**
     * Event name to observe
     */
    const EVENT_NAME = 'event-name';

    /**
     * Value to add to the module.xml setup_version attribute
     */
    const SETUP_VERSION = 'setup-version';

    /**
     * ViewModel/ subdirectory path
     */
    const VIEW_MODEL_SUBDIRECTORY_PATH = 'view-model-subdirectory-path';

    /**
     * View Model class name
     */
    const VIEW_MODEL_CLASS_NAME = 'view-model-class-name';

    /**
     * Class name of Block we're adding View Model to
     */
    const VIEW_MODEL_BLOCK_CLASS_NAME = 'view-model-block-class-name';

    /**
     * Name used to access the View Model in the Block/Template
     */
    const LAYOUT_XML_VIEW_MODEL_NAME = 'layout-xml-view-model-name';

    /**
     * Layout XML name of Block we're adding View Model to
     */
    const LAYOUT_XML_BLOCK_NAME = 'layout-xml-block-name';
}
