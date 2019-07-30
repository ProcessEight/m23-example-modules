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

class RegisterCustomer extends AbstractRestApiExample
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("processeight:examples:querying-the-rest-api:register-customer");
        $this->setDescription("Demonstrates how to query the REST API in Magento 2 to create a new customer.");

        parent::configure();
    }

    /**
     * This example shows a simplified way of creating a customer account. Typically, you would not define a customer
     * password using plain text. Instead, you would specify the payload without the password parameter. By default if
     * the call is successful, Magento sends a “Welcome” email to the customer that includes a request to set the
     * password. You could also initiate a password reset email by calling PUT /V1/customers/password.
     *
     * @link http://devdocs.magento.com/guides/v2.1/get-started/order-tutorial/order-create-customer.html
     *
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateAdminToken();

        /*
         * Creates a new customer
         */
        $options['json'] = [
            'customer' => [
                'email'     => 'simon.frost@example.com', // Create a unique email each time (for debugging)
                'firstname' => 'Simon',
                'lastname'  => 'Frost',
                'addresses' => [
                    [
                        'defaultShipping' => true,
                        'defaultBilling'  => true,
                        'firstname'       => 'Simon',
                        'lastname'        => 'Frost',
                        'region'          => [
                            'regionCode' => '',
                            'region'     => 'North Yorkshire',
                        ],
                        'postcode'        => 'AB1 2CD',
                        'street'          => ['1 Test Street'],
                        'city'            => 'York',
                        'telephone'       => '01904 123456',
                        'countryId'       => 'GB',
                    ],
                ],
            ],
            'password' => 'CustomerPassword123',
        ];

        $options['headers'] = [
            "Content-Type"  => "application/json",
            "Authorization" => "Bearer {$this->adminToken}",
        ];

        $response = $this->guzzleHttpClient->request('POST', 'customers', $options);

        $newCustomerJson = (string)$response->getBody();

        $output->writeln("Created customer with details: ");
        $output->writeln(var_export(\GuzzleHttp\json_decode($newCustomerJson, true)) . "\n");
    }
}
