<?php
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

// @todo Uncomment and test this
//declare(strict_types=1);

namespace ProcessEight\CheckoutAddComponentToSidebarExample\Model\Checkout;

/**
 * Class BasicConfigProvider
 *
 * Provides a means to pass data from server side to client side checkout uiComponent
 * This can be thought of as an equivalent of a Block class or a ViewModel class,
 * i.e. They all provide a mechanism for passing data from the backend to a frontend template
 *
 * @package ProcessEight\CheckoutAddComponentToSidebarExample\Model\Checkout
 */
class BasicConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    const MINIMUM_AMOUNT = 10;
    const AMOUNT_EDGE = 100;
    const IMAGE = 'ProcessEight_CheckoutAddComponentToSidebarExample/images/foolsample2.gif';
    const IMAGE2 = 'ProcessEight_CheckoutAddComponentToSidebarExample/images/foolsample3.gif';
    const IMAGER = 'ProcessEight_CheckoutAddComponentToSidebarExample/images/foolsampler.gif';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * We're just passing an array of hardcoded values here, but the values could come from anywhere,
     * e.g. Database, Quote, etc
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [
            'foolsample' => [
                'minimum_amount' => self::MINIMUM_AMOUNT,
                'amount_edge'    => self::AMOUNT_EDGE,
                'image'          => self::IMAGE,
                'image2'         => self::IMAGE2,
                'imageR'         => self::IMAGER,
            ],
        ];

        return $config;
    }
}
