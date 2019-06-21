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

namespace ProcessEight\AddAdminUsersExample\Setup;

use Magento\Authorization\Model\UserContextInterface;
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
     * @var \Magento\Authorization\Model\ResourceModel\Role\Collection
     */
    private $roleCollectionFactory;

    /**
     * @var \Magento\Framework\File\Csv
     */
    private $csvProcessor;

    /**
     * Constructor
     *
     * @param \Magento\User\Api\Data\UserInterfaceFactory                       $userFactory
     * @param \Magento\User\Model\ResourceModel\User                            $userResourceModel
     * @param \Magento\Authorization\Model\ResourceModel\Role\CollectionFactory $roleCollectionFactory
     * @param \Magento\Framework\File\Csv                                       $csvProcessor
     */
    public function __construct(
        \Magento\User\Api\Data\UserInterfaceFactory $userFactory,
        \Magento\User\Model\ResourceModel\User $userResourceModel,
        \Magento\Authorization\Model\ResourceModel\Role\CollectionFactory $roleCollectionFactory,
        \Magento\Framework\File\Csv $csvProcessor
    ) {
        $this->userFactory           = $userFactory;
        $this->userResourceModel     = $userResourceModel;
        $this->roleCollectionFactory = $roleCollectionFactory;
        $this->csvProcessor          = $csvProcessor;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $userRoles = $this->getUserRoleNames();

        foreach ($this->getUserData() as $key => $user) {
            /** @var \Magento\User\Model\User $user */
            $user = $this->userFactory->create([
                'data' => [
                    'firstname'           => $user['firstname'],
                    'lastname'            => $user['lastname'],
                    'email'               => $user['email'],
                    'username'            => $user['username'],
                    'password'            => '!!!correct-horse-battery-stapler-123',
                    'role_id'             => $userRoles[$user['role_name']],
                    'is_active'           => 1,
                    'interface_locale'    => 'en_GB',
                    'rp_token'            => null,
                    'rp_token_created_at' => null,

                ],
            ]);
            $user->setDataChanges(true);

            $this->userResourceModel->save($user);

            $this->forcePasswordResetOnFirstTimeLogin((int)$user->getId(), $user->getPassword());
        }
    }

    /**
     * Get all admin user role names
     *
     * @return \Magento\Authorization\Model\Role[]
     */
    private function getUserRoleNames() : array
    {
        /** @var \Magento\Authorization\Model\ResourceModel\Role\Collection $userRoleCollection */
        $userRoleCollection = $this->roleCollectionFactory->create();
        $userRoleCollection->setRolesFilter();
        $userRoleCollection->setUserFilter(0, UserContextInterface::USER_TYPE_ADMIN);
        $userRoleCollection->addFieldToFilter('parent_id', 0)
                           ->addFieldToFilter('tree_level', 1)
                           ->addFieldToSelect(['role_id', 'role_name']);

        $users = [];
        foreach ($userRoleCollection->getItems() as $item) {
            $users[$item->getDataByKey('role_id')] = $item->getDataByKey('role_name');
        }

        return array_flip($users);
    }

    /**
     * Parse the CSV into a multi-dimensional array, with each line in the CSV being a new array element
     *
     * @return array[]
     * @throws \Exception
     */
    private function getUserData() : array
    {
        $data = $this->csvProcessor->getData(__DIR__ . DIRECTORY_SEPARATOR . 'admin_users.csv');
        array_walk($data, function (&$a) use ($data) {
            $a = array_combine($data[0], $a);
        });
        array_shift($data);

        return $data;
    }

    /**
     * Trick Magento to think the password has expired, to force the user to change their password on first time login
     *
     * @param int    $userId
     * @param string $userPasswordHash
     */
    private function forcePasswordResetOnFirstTimeLogin(int $userId, string $userPasswordHash)
    {
        $this->userResourceModel->getConnection()->insert(
            $this->userResourceModel->getTable('admin_passwords'),
            [
                'user_id'       => $userId,
                'password_hash' => $userPasswordHash,
                'expires'       => 0,
                'last_updated'  => strtotime('now - 91 day'),
            ]
        );
    }
}
