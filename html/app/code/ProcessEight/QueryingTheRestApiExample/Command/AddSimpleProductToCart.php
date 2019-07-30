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

class AddSimpleProductToCart extends AbstractRestApiExample
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("processeight:examples:querying-the-rest-api:add-simple-product-to-cart");
        $this->setDescription("Demonstrates how to use the REST API in Magento 2 to add a simple product "
                              . "to a pre-existing cart for a registered customer");

        parent::configure();
    }

    /**
     * Add a simple product to the cart
     *
     * @link http://devdocs.magento.com/guides/v2.2/get-started/order-tutorial/order-add-items.html
     *
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateCustomerToken();

        $options['headers'] = [
            'Content-Type'  => 'application/json',
            'Authorization' => "Bearer " . trim($this->customerToken, '"'),
        ];

        $options['json'] = [
            'cartItem' => [
                'sku'      => 'WS12-M-Orange',
                'qty'      => 1,
                'quote_id' => 3,
            ],
        ];

        $response = $this->guzzleHttpClient->request('POST', 'carts/mine/items', $options);

        $quoteItemJson = (string)$response->getBody();

        $output->writeln("Added the following product to the cart: ");
        $output->writeln(var_export(\GuzzleHttp\json_decode($quoteItemJson, true)) . "\n");
    }
}
