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

namespace ProcessEight\CreateStoreViewGroupWebsite\Setup;

use Exception;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    private $websiteFactory;

    /**
     * @var \Magento\Store\Model\GroupFactory
     */
    private $groupFactory;

    /**
     * @var \Magento\Store\Model\StoreFactory
     */
    private $storeFactory;

    /**
     * @var \Magento\Store\Model\ResourceModel\Website
     */
    private $websiteResourceModel;

    /**
     * @var \Magento\Store\Model\ResourceModel\Group
     */
    private $groupResourceModel;

    /**
     * @var \Magento\Store\Model\ResourceModel\Store
     */
    private $storeResourceModel;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    private $categoryResourceModel;

    /**
     * InstallData constructor.
     *
     * @param \Magento\Store\Model\WebsiteFactory           $websiteFactory
     * @param \Magento\Store\Model\GroupFactory             $groupFactory
     * @param \Magento\Store\Model\StoreFactory             $storeFactory
     * @param \Magento\Store\Model\ResourceModel\Website    $websiteResourceModel
     * @param \Magento\Store\Model\ResourceModel\Group      $groupResourceModel
     * @param \Magento\Store\Model\ResourceModel\Store      $storeResourceModel
     * @param \Magento\Catalog\Model\CategoryFactory        $categoryFactory
     * @param \Magento\Catalog\Model\ResourceModel\Category $categoryResourceModel
     */
    public function __construct(
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Magento\Store\Model\GroupFactory $groupFactory,
        \Magento\Store\Model\StoreFactory $storeFactory,
        \Magento\Store\Model\ResourceModel\Website $websiteResourceModel,
        \Magento\Store\Model\ResourceModel\Group $groupResourceModel,
        \Magento\Store\Model\ResourceModel\Store $storeResourceModel,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResourceModel
    ) {
        $this->websiteFactory        = $websiteFactory;
        $this->groupFactory          = $groupFactory;
        $this->storeFactory          = $storeFactory;
        $this->websiteResourceModel  = $websiteResourceModel;
        $this->groupResourceModel    = $groupResourceModel;
        $this->storeResourceModel    = $storeResourceModel;
        $this->categoryFactory       = $categoryFactory;
        $this->categoryResourceModel = $categoryResourceModel;
    }

    /**
     * Installs data for a module
     *
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface   $context
     *
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $rootCategory = $this->createRootCategory();
        $website      = $this->createWebsite();
        $group        = $this->createStoreGroup((int)$website->getId(), (int)$rootCategory->getId());
        $store        = $this->createStoreView((int)$group->getId(), (int)$website->getId());

        /** Assign store VIEW to store GROUP */
        $group->setDefaultStoreId($store->getId());
        $this->groupResourceModel->save($group);

        /** Assign store GROUP to WEBSITE */
        $website->setDefaultGroupId($group->getId());
        $this->websiteResourceModel->save($website);
    }

    /**
     * Create a new root category, for the purposes of this example
     *
     * @throws Exception
     */
    private function createRootCategory()
    {
        $defaultCategory = $this->categoryFactory->create();
        $defaultCategory->setName('Pluto Root Category');
        $defaultCategory->setIsActive(true);
        $defaultCategory->setIncludeInMenu(true);
        $defaultCategory->setParentId(1);
        $defaultCategory->setPath(1);

        $this->categoryResourceModel->save($defaultCategory);

        return $defaultCategory;
    }

    /**
     * Create a new website
     *
     * @return \Magento\Store\Model\Website
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    private function createWebsite()
    {
        $website = $this->websiteFactory->create();
        $website->setName('Pluto Website');
        // Website code may contain only lowercase letters (a-z), numbers (0-9) or underscore (_) and the first character must be a letter
        $website->setCode('pluto_website');
        $website->setIsDefault(true); // Optional

        $this->websiteResourceModel->save($website);

        return $website;
    }

    /**
     * Create a new store group (confusingly, Magento calls this a 'store' in the admin)
     *
     * @param int $websiteId
     * @param int $rootCategoryId
     *
     * @return \Magento\Store\Model\Group
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    private function createStoreGroup(int $websiteId, int $rootCategoryId)
    {
        $group = $this->groupFactory->create();
        $group->setName('Pluto Store Group');
        $group->setCode('pluto_group');
        // A root category can only be assigned to one website at a time, so we create an example one here
        $group->setRootCategoryId($rootCategoryId);
        $group->setWebsiteId($websiteId);

        $this->groupResourceModel->save($group);

        return $group;
    }

    /**
     * Create a new store view
     *
     * @param int $groupId
     * @param int $websiteId
     *
     * @return \Magento\Store\Model\Store
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    private function createStoreView(int $groupId, int $websiteId)
    {
        $store = $this->storeFactory->create();
        $store->setName('Pluto Store View (en_GB)');
        $store->setCode('pluto_store');
        $store->setGroupId($groupId);
        $store->setWebsiteId($websiteId);
        $store->setIsActive(true);

        $this->storeResourceModel->save($store);

        return $store;
    }
}
