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

declare(strict_types=1);

namespace ProcessEight\QueryingTheRestApiExample\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddConfigurableProductToCart extends AbstractRestApiExample
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("processeight:examples:querying-the-rest-api:add-configurable-product-to-cart");
        $this->setDescription("Demonstrates how to use the REST API in Magento 2 to add a configurable product "
                              . "to a pre-existing cart of a registered customer");

        parent::configure();
    }

    /**
     * Add a configurable product to the cart
     *
     * To add a configurable product to a cart, you must specify the sku as well as the set of option_id/option_value
     * pairs that make the product configurable.
     *
     * In this example, we’ll add the Chaz Kangeroo Hoodie (sku: MH01) configurable product to the cart.
     * This product comes in three colors (black, gray, and orange) and five sizes (XS, S, M, L, XL).
     * In the sample data, the option_id values for Size and Color are 141 and 93, respectively.
     * You can use the GET /V1/configurable-products/:sku/options/all call to determine the option_id values
     * for the given SKU.
     *
     * @link http://devdocs.magento.com/guides/v2.2/get-started/order-tutorial/order-add-items.html
     *
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $optionIds = $this->getOptionIds();

        $options['json'] = [
            'cartItem' => [
                'sku'                  => 'MH01',
                'qty'                  => 1,
                'quote_id'             => 3,
                'product_option'       => [
                    'extension_attributes' => [
                        'configurable_item_options' => [
                            [
                                // The 'size' attribute ID
                                'option_id'    => $optionIds[0]['attribute_id'],
                                // The 'size' attribute value ID, e.g. 168 for 'small'
                                'option_value' => $optionIds[0]['values'][1]['value_index'],
                            ],
                            [
                                // The 'color' attribute ID
                                'option_id'    => $optionIds[1]['attribute_id'],
                                // The 'color' attribute value ID, e.g. 52 for 'gray'
                                'option_value' => $optionIds[1]['values'][1]['value_index'],
                            ],
                        ],
                    ],
                ],
                'extension_attributes' => [],
            ],
        ];

        $this->generateCustomerToken();

        $options['headers'] = [
            'Content-Type'  => 'application/json',
            'Authorization' => "Bearer " . trim($this->customerToken, '"'),
        ];

        $response = $this->guzzleHttpClient->request('POST', 'carts/mine/items', $options);

        $quoteItemJson = (string)$response->getBody();

        $output->writeln("Added the following product to the cart: ");
        $output->writeln(var_export(\GuzzleHttp\json_decode($quoteItemJson, true)) . "\n");
    }

    /**
     * Get the option IDs (attribute IDs) and option value IDs of the configurable product options
     *
     * @return string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getOptionIds()
    {
        $this->generateAdminToken();

        $options['headers'] = [
            'Content-Type'  => 'application/json',
            'Authorization' => "Bearer " . trim($this->adminToken, '"'),
        ];

        $sku = "MH01";

        $response = $this->guzzleHttpClient->request('GET', "configurable-products/{$sku}/options/all", $options);

        $configurableProductAttributeOptionIds = (string)$response->getBody();

        return json_decode($configurableProductAttributeOptionIds, true);
    }
}
