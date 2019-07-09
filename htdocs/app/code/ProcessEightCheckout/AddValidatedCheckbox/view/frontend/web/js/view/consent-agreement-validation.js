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
 * Register the validator which will prevent progress to next step if the data processing checkbox is not ticked
 */

define([
    'uiComponent',
    'ProcessEightCheckout_AddNewStepToBeginning/js/model/step-validator',
    'ProcessEightCheckout_AddValidatedCheckbox/js/model/consent-agreement-validator'
], function (Component, stepValidator, consentAgreementValidator) {
    'use strict';

    stepValidator.registerValidator(consentAgreementValidator);

    return Component.extend({});
});
