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
 * This file is the equivalent of a 'before' plugin.
 * We 'extend' the Magento_Checkout/js/model/shipping-save-processor/default::saveShippingInformation() method
 * in order to execute our logic first, passing the original method as a parameter.
 * Then we return _super(), which calls the original method, which is the equivalent of calling parent::{{method_name}} in PHP.
 * The 'extended' file is located at /vendor/magento/module-checkout/view/frontend/web/js/model/shipping-save-processor/default.js
 */
define([
    'mage/utils/wrapper',
], function (wrapper) {
    'use strict';

    var extender = {
        saveShippingInformation: function (_super) {
            // This should appear in the console when the customer moves from the shipping stage to the payment/review stage of checkout
            console.log('ABC123');

            return _super();
        }
    };

    /**
     * 'Wrap' the original class with our class, a bit like how an Interceptor class extends the target class in PHP
     */
    return function (target) {
        return wrapper.extend(target, extender);
    };
});
