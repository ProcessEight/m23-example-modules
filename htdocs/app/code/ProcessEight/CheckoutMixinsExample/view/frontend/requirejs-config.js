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
/**
 * This config file is merged with all the requirejs-config.js files of all the other modules
 * and then sent as one file to the browser
 */

var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/shipping-save-processor/default': {
                'ProcessEight_CheckoutMixinsExample/js/model/shipping-save-processor/default-mixin': true
            }
        }
    }
};
