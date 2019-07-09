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
 * Manages validators for a single checkout step
 */

define([], function () {
    'use strict';

    let validators = [];

    return {
        /**
         * Register unique validator
         *
         * @param {*} validator
         */
        registerValidator: function (validator) {
            validators.push(validator);
        },

        /**
         * Returns array of registered validators
         *
         * @returns {Array}
         */
        getValidators: function () {
            return validators;
        },

        /**
         * Process validators
         *
         * @returns {Boolean}
         */
        validate: function (hideError) {
            var validationResult = true;

            hideError = hideError || false;

            if (validators.length <= 0) {
                return validationResult;
            }

            validators.forEach(function (item) {
                if (item.validate(hideError) == false) { //eslint-disable-line eqeqeq
                    validationResult = false;

                    return false;
                }
            });

            return validationResult;
        }
    };
});
