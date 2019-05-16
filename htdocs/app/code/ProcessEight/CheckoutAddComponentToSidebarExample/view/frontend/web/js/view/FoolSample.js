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
 * This file contains all the logic of our uiComponent, i.e. It IS a uiComponent.
 * It can be thought of as a Block class, but in JavaScript, in that its' purpose is to provide all the logic
 * that this uiComponents' template needs to render.
 * The template is located in view/frontend/web/template/checkout/foolsample.html
 */
define([
    'jquery', // @todo Do we need to include jQuery?
    'uiComponent',
    'Magento_Checkout/js/model/quote',
], function ($, Component, quote) {
    'use strict';

    /**
     * Grabs the grand total from the quote
     * @return {Number}
     */
    let getTotal = function () {
        return quote.totals()['grand_total'];
    };

    let getQuote = function () {
        return quote;
    };

    return Component.extend({ // Extend the base uiComponent

        defaults: {
            // Override the default template with our own
            // The template is located in view/frontend/web/template/checkout/foolsample.html
            template: 'ProcessEight_CheckoutAddComponentToSidebarExample/checkout/foolsample'
        },

        /**
         * Init component
         */
        initialize: function () {
            // Call parent initialize method
            this._super();
            // Populate the Component with config from the checkoutConfig
            // The values are defined in \ProcessEight\CheckoutAddComponentToSidebarExample\Model\Checkout\BasicConfigProvider
            this.config = window.checkoutConfig.foolsample;
        },

        getTitle: function () {
            // This value is defined in view/frontend/layout/checkout_index_index.xml:38
            return this.title;
        },

        getImage: function () {
            if (getQuote().shippingAddress()) {
                if (getQuote().shippingAddress().countryId
                    && getQuote().shippingAddress().countryId.toLowerCase() === 'us') {
                    return require.toUrl(this.config.image2);
                }
            }

            if (getTotal() >= this.config.amount_edge) {
                return require.toUrl(this.config.image3);
            }
            return require.toUrl(this.config.image1);
        },

        isVisible: function () {
            return (getTotal() >= this.config.minimum_amount);
        }
    })
});
