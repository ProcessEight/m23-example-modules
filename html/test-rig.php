<?php

declare(strict_types=1);

require_once 'app/bootstrap.php';

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

// Any code you want here...For example:

/** @var \Magento\Backend\Model\UrlInterface $url */
$url = $bootstrap->getObjectManager()->create(\Magento\Backend\Model\UrlInterface::class);

$backendUrl = $url->getUrl(
    'adminhtml/dashboard/index',
    ['_secure' => false,]
);

var_dump($backendUrl); // string(130) "http://m23-example-modules.local/admin/admin/dashboard/index/key/7fc719bb562ca73ad72a45dc7ecde8c02140f7dc283d95ea59ee62594b5301c1/"
