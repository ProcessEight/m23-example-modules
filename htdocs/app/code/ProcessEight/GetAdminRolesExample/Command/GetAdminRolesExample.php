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

namespace ProcessEight\GetAdminRolesExample\Command;

use Magento\Authorization\Model\UserContextInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GetAdminRolesExample
 *
 * Demonstration of how to programmatically retrieve all admin roles
 *
 * @package ProcessEight\GetAdminRolesExample\Command
 */
class GetAdminRolesExample extends Command
{
    const USER_ID = 0;
    const PARENT_ID_CONDITION = 0;
    const TREE_LEVEL_CONDITION = 1;

    /**
     * @var \Magento\Authorization\Model\ResourceModel\Role\CollectionFactory
     */
    private $roleCollectionFactory;

    /**
     * Constructor.
     *
     * @param \Magento\Authorization\Model\ResourceModel\Role\CollectionFactory $roleCollectionFactory
     */
    public function __construct(
        \Magento\Authorization\Model\ResourceModel\Role\CollectionFactory $roleCollectionFactory
    ) {
        parent::__construct();
        $this->roleCollectionFactory = $roleCollectionFactory;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:example:get-admin-roles");
        $this->setDescription("Lists all admin roles");
        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Magento\Authorization\Model\ResourceModel\Role\Collection $userRoleCollection */
        $userRoleCollection = $this->roleCollectionFactory->create();
        $userRoleCollection->setRolesFilter();
        $userRoleCollection->setUserFilter(self::USER_ID, UserContextInterface::USER_TYPE_ADMIN);
        $userRoleCollection->addFieldToFilter('parent_id', self::PARENT_ID_CONDITION)
                           ->addFieldToFilter('tree_level', self::TREE_LEVEL_CONDITION);

        $table = new \Symfony\Component\Console\Helper\Table($output);
        $table->setHeaders([
            'role_id',
            'parent_id',
            'tree_level',
            'sort_order',
            'role_type',
            'user_id',
            'user_type',
            'role_name',
        ]);
        $rows = [];
        foreach ($userRoleCollection as $userRole) {
            /** @var \Magento\Authorization\Model\Role $userRole */
            $rows[] = $userRole->getData();
        }
        $table->setRows($rows);
        $table->render();

        return null;
    }
}
