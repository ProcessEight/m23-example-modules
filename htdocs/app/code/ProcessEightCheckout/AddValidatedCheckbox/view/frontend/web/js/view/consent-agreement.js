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
 * View model for the consent-agreement UI component
 */

define([
    'ko',
    'jquery',
    'uiComponent',
], function (ko, $, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'ProcessEightCheckout_AddValidatedCheckbox/checkout/consent-agreement'
        },
        isVisible: 1,
        agreements: [{content: 'This is my content', checkboxText: '1735 I acknowledge I have read the privacy policy and consent to receive order emails.', mode: 1, agreementId: 1}],

        /**
         * Build a unique id for the consent checkbox
         *
         * @param {Object} context - the ko context
         * @param {Number} agreementId
         */
        getCheckboxId: function (context, agreementId) {
            return 'consent_agreement_' + agreementId;
        },
    });
});
