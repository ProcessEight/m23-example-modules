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

define(
    [
        'ko'
    ], function (ko) {
        'use strict';

        var mixin = {

            /**
             * Initialise component
             *
             * @returns {mixin}
             */
            initialize: function () {
                // Set visible to be initially false to have your step show first
                this.isVisible = ko.observable(false);
                this._super();

                return this;
            }
        };

        return function (target) {
            return target.extend(mixin);
        };
    }
);
