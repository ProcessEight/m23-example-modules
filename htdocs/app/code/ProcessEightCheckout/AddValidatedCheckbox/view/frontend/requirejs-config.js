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
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'ProcessEightCheckout_AddValidatedCheckbox/js/model/place-order-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'ProcessEightCheckout_AddValidatedCheckbox/js/model/set-payment-information-mixin': true
            }
        }
    }
};
