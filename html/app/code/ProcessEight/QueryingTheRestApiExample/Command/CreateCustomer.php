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

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCustomer extends Command
{
    /**
     * Guzzle HTTP Client
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzleHttpClient;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("processeight:examples:querying-the-rest-api:create-customer");
        $this->setDescription("Demonstrates how to query the REST API in Magento 2 to create a new customer.");

        $this->initClient();

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $adminToken = $this->getAdminToken();

        /*
         * Creates a new customer
         * See http://devdocs.magento.com/guides/v2.1/get-started/order-tutorial/order-create-customer.html
         */
        $options['json'] = [
            'customer' => [
                'email'     => 'simon.frost2037@example.com',
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
            'password' => 'Password123',
        ];

        $options['headers'] = [
            "Content-Type"  => "application/json",
            "Authorization" => "Bearer {$adminToken}",
        ];

        $response = $this->guzzleHttpClient->request('POST', 'customers', $options);

        $newCustomerJson = (string)$response->getBody();

        $output->writeln("Created customer with details: ");
        $output->writeln(print_r(\GuzzleHttp\json_decode($newCustomerJson, true)));
    }

    /**
     * Run the get-admin-token command to get an admin token
     *
     * @return false|string
     */
    protected function getAdminToken()
    {
        /*
         * First we need to get our admin token.
         * See http://devdocs.magento.com/guides/v2.1/get-started/order-tutorial/order-admin-token.html
         */
        $data['json']       = [
            'username' => 'admin',
            'password' => 'password123',
        ];
        $headers['headers'] = [
            "Content-Type" => "application/json",
        ];
        $options['json']    = $data['json'];
        $options['headers'] = $headers['headers'];

        $response = $this->guzzleHttpClient->request('POST', 'integration/admin/token', $options);

        $adminToken = (string)$response->getBody();

        return $adminToken;

//        $command    = $this->getApplication()->find('processeight:examples:querying-the-rest-api:get-admin-token');
//        $bufferedOutput     = new BufferedOutput();
//        $resultCode = $command->run($input, $bufferedOutput);
//
//        if ($resultCode !== 0) {
//            $bufferedOutput->writeln('Failed to initialise Guzzle HTTP Client; Cannot continue.');
//
//            return false;
//        }
//
//        return $bufferedOutput->fetch();
    }

    /**
     * Initialise client
     *
     * @return Client
     */
    public function initClient()
    {
        $this->guzzleHttpClient = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://magento2-sample-modules.localhost.com/index.php/rest/default/V1/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        return $this->guzzleHttpClient;
    }
}
