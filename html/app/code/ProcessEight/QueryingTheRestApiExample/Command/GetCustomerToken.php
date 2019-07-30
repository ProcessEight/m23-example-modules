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

class GetCustomerToken extends AbstractRestApiExample
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("processeight:examples:querying-the-rest-api:get-customer-token");
        $this->setDescription("Demonstrates how to query the REST API in Magento 2 to get a customer token.");

        parent::configure();
    }

    /**
     * To get a customer’s access token, you must specify the customer’s username and password in the payload.
     * You do not need to specify an admin authorization token.
     * By default, a customer token is valid for 1 hour. To change this value, log in to Admin and go to
     * Configuration > Services > OAuth > Access Token Expiration.
     *
     * @link http://devdocs.magento.com/guides/v2.2/get-started/order-tutorial/order-create-customer.html#get-token
     *
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options['json'] = [
            'username' => 'simon.frost@example.com',
            'password' => 'CustomerPassword123',
        ];

        $options['headers'] = [
            'Content-Type' => 'application/json',
        ];

        $response = $this->guzzleHttpClient->request('POST', 'integration/customer/token', $options);

        $customerToken = (string)$response->getBody();

        $output->writeln($customerToken . "\n");
    }
}
