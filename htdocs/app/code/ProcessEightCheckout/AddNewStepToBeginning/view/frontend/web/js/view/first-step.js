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
        'ProcessEightCheckout_AddValidatedCheckbox/js/model/welcome/additional-validators',
        'Magento_Checkout/js/model/step-navigator'
    ],
    function (
        ko,
        Component,
        _,
        stepValidator,
        stepNavigator
    ) {
        'use strict';

        /**
         * Initialise our UI component. Similar to calling __construct() in a PHP class.
         *
         */
        return Component.extend({
            defaults: {
                // Maps to htdocs/app/code/ProcessEightCheckout/AddNewStepToBeginning/view/frontend/web/template/first-step.html
                template: 'ProcessEightCheckout_AddNewStepToBeginning/first-step'
            },

            // Make sure our step is the first one to be visible when the checkout loads
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
                    'welcome-step',
                    // Step alias(?)
                    null,
                    // Step title
                    'Welcome',
                    // Observable property; Used to hide/show the step as appropriate
                    this.isVisible,
                    _.bind(this.navigate, this),
                    // Sort order value
                    // 'sort order value' < 10: step displays before shipping step;
                    // 10 < 'sort order value' < 20 : step displays between shipping and payment step;
                    // 'sort order value' > 20 : step displays after payment step.
                    1
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
             * Check if the consent agreement checkbox has been ticked and proceed to next step if so
             * @returns void
             */
            navigateToNextStep: function () {
                if (stepValidator.validate()) {
                    stepNavigator.next();
                }
            }
        });
    }
);
