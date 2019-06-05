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

namespace ProcessEight\CreateAdminUser\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\User\Api\Data\UserInterfaceFactory
     */
    private $userFactory;
    /**
     * @var \Magento\User\Model\ResourceModel\User
     */
    private $userResourceModel;

    /**
     * Constructor
     *
     * @param \Magento\User\Api\Data\UserInterfaceFactory $userFactory
     * @param \Magento\User\Model\ResourceModel\User      $userResourceModel
     */
    public function __construct(
        \Magento\User\Api\Data\UserInterfaceFactory $userFactory,
        \Magento\User\Model\ResourceModel\User $userResourceModel
    ) {
        $this->userFactory       = $userFactory;
        $this->userResourceModel = $userResourceModel;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $adminUsers = [
            [
                'firstname' => 'Joe',
                'lastname'  => 'Bloggs',
                'email'     => 'joe.bloggs@example.com',
                'role_id'   => 1,
            ],
            [
                'firstname' => 'John',
                'lastname'  => 'Doe',
                'email'     => 'doe.john@example.com',
                'role_id'   => 1,
            ],
        ];

        foreach ($adminUsers as $key => $adminUser) {
            /** @var \Magento\User\Model\User $adminUser */
            $adminUser = $this->userFactory->create([
                'data' => [
                    'firstname'        => $adminUser['firstname'],
                    'lastname'         => $adminUser['lastname'],
                    'email'            => $adminUser['email'],
                    'username'         => $adminUser['email'],
                    'password'         => '!!!correct-horse-battery-stapler-123',
                    'role_id'          => $adminUser['role_id'],
                    'is_active'        => 1,
                    'interface_locale' => 'en_GB',
                ],
            ]);
            $adminUser->setDataChanges(true);

            $this->userResourceModel->save($adminUser);
        }
    }
}
