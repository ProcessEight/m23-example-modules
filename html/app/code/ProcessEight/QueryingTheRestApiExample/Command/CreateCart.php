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

class CreateCart extends AbstractRestApiExample
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("processeight:examples:querying-the-rest-api:create-cart");
        $this->setDescription("Demonstrates how to query the REST API in Magento 2 to create a cart for a registered customer.");

        parent::configure();
    }

    /**
     * Create a new, empty cart for a registered and logged-in customer
     *
     * @link http://devdocs.magento.com/guides/v2.2/get-started/order-tutorial/order-create-quote.html
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
        $response           = $this->guzzleHttpClient->request('POST', 'carts/mine', $options);

        $cartId = (string)$response->getBody();

        $output->writeln("Created a new cart with ID: ");
        $output->writeln($cartId . "\n");
    }
}
