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
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 ProcessEight
 * @author      ProcessEight
 *
 */

namespace ProcessEight\ModuleManager\Model;

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
}
