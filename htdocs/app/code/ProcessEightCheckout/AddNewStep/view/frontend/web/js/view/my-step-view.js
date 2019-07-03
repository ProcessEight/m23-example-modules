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
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator
    ) {
        'use strict';
        /**
         *
         * mystep - is the name of the component's .html template,
         * ProcessEightCheckout_AddNewStep  - is the name of the your module directory.
         *
         */
        return Component.extend({
            defaults: {
                template: 'ProcessEightCheckout_AddNewStep/mystep'
            },

            // Add here your logic to display step,
            isVisible: ko.observable(true),

            /**
             * Initialise component
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();
                // Register your step
                stepNavigator.registerStep(
                    // step_code will be used as step content id in the component template
                    'step_code',
                    // Step alias(?)
                    null,
                    // Step title
                    'Step Title',
                    // Observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
                     * Sort order value
                     * 'sort order value' < 10: step displays before shipping step;
                     * 10 < 'sort order value' < 20 : step displays between shipping and payment step;
                     * 'sort order value' > 20 : step displays after payment step.
                     */
                    15
                );

                return this;
            },

            /**
             * The navigate() method is responsible for navigation between checkout steps during checkout.
             * You can add custom logic, for example some conditions for switching to your custom step.
             * When the user navigates to the custom step via URL anchor or back button we must show step manually here.
             */
            navigate: function () {
                this.isVisible(true);
            },

            /**
             * @returns void
             */
            navigateToNextStep: function () {
                stepNavigator.next();
            }
        });
    }
);
