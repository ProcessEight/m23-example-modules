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
/**
 * Validate that the data processing consent checkbox has been ticked
 */

define([
    'jquery',
    'mage/validation'
], function ($) {
    'use strict';

    let agreementsInputPath = 'div.consent-agreement input';

    return {

        /**
         * Validate consent agreement checkbox
         *
         * @returns {Boolean}
         */
        validate: function (hideError) {
            let isValid = true;

            if ($(agreementsInputPath).length === 0) {
                return false;
            }

            $(agreementsInputPath).each(function (index, element) {
                if (!$.validator.validateSingleElement(element, {
                    errorElement: 'div',
                    hideError: hideError || false
                })) {
                    isValid = false;
                }
            });

            return isValid;
        }
    };
});
