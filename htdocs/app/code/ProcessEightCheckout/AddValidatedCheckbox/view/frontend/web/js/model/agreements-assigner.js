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
 * Overrides the default place order action and adds the agreement_id (from the checkbox) to the request
 */

/*global alert*/
define([
    'jquery'
], function ($) {
    'use strict';

    /** Override default place order action and add agreement_ids to request */
    return function (paymentData) {
        let agreementForm,
            agreementData,
            agreementIds;

        agreementForm = $('div[data-role=consent-agreement] input');
        agreementData = agreementForm.serializeArray();
        agreementIds = [];

        agreementData.forEach(function (item) {
            agreementIds.push(item.value);
        });

        if (paymentData['extension_attributes'] === undefined) {
            paymentData['extension_attributes'] = {};
        }

        paymentData['extension_attributes']['agreement_ids'] = agreementIds;
    };
});
