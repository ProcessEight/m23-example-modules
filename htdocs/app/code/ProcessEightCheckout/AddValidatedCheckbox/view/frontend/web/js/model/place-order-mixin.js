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
 * Wrap the default place order action in an overriding function so we can call our model (agreementsAssigner) first
 */

define([
    'jquery',
    'mage/utils/wrapper',
    'ProcessEightCheckout_AddValidatedCheckbox/js/model/agreements-assigner'
], function ($, wrapper, agreementsAssigner) {
    'use strict';

    return function (placeOrderAction) {

        /** Override default place order action and add agreement_ids to request */
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {
            agreementsAssigner(paymentData);

            return originalAction(paymentData, messageContainer);
        });
    };
});
