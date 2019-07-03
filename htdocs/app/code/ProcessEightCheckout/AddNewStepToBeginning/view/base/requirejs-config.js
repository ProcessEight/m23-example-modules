/**
 * ProcessEightCheckout
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEightCheckout for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 ProcessEightCheckout
 * @author      ProcessEightCheckout
 *
 */

var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/shipping': {
                'ProcessEightCheckout_AddNewStepToBeginning/js/view/shipping-payment-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'ProcessEightCheckout_AddNewStepToBeginning/js/view/shipping-payment-mixin': true
            }
        }
    }
};
