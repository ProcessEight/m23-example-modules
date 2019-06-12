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

namespace ProcessEight\AddCmsPageExample\Setup;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * Page Factory
     *
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Page Repository
     *
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * Constructor
     *
     * @param PageFactory             $pageFactory
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(PageFactory $pageFactory, PageRepositoryInterface $pageRepository)
    {
        $this->pageFactory    = $pageFactory;
        $this->pageRepository = $pageRepository;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Basic example to add a new page
         */
        $examplePageContent = <<<EOD
<div class="example-page cms-content">
    <div class="message info">
        <span>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer 
            took a galley of type and scrambled it to make a type specimen book. 
        </span>
        <span>
            It has survived not only five centuries, but also the leap into electronic typesetting, 
            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets 
            containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker 
            including versions of Lorem Ipsum.
        </span>
    </div>
</div>
EOD;

        /**
         * The full list of data keys can be found in \Magento\Cms\Api\Data\PageInterface
         */
        $examplePageData = [
            PageInterface::TITLE           => 'Example CMS page',
            PageInterface::CONTENT_HEADING => 'Example CMS page',
            // One of empty, 1column, 2columns-left, 2columns-right, 3columns
            PageInterface::PAGE_LAYOUT     => '1column',
            // Must be unique
            PageInterface::IDENTIFIER      => 'example-cms-page',
            PageInterface::CONTENT         => $examplePageContent,
            PageInterface::IS_ACTIVE       => 1,
            // Either 0 for all sites or an array of store IDs
            'stores'                       => [\Magento\Store\Model\Store::DEFAULT_STORE_ID],
            PageInterface::SORT_ORDER      => 0,
        ];

        $examplePageModel = $this->pageFactory->create();
        $examplePageModel->setData($examplePageData);
        $this->pageRepository->save($examplePageModel);

        /**
         * Or you can inject data at the time of creation. Both methods are equivalent
         */
//        $examplePageModel = $this->pageFactory->create(
//            ['data' => $examplePageData,]
//        );
//        $this->pageRepository->save($examplePageModel);

        $setup->endSetup();
    }
}
