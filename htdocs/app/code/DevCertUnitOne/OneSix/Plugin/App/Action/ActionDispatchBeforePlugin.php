<?php /** @noinspection PhpUndefinedClassInspection */
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

namespace DevCertUnitOne\OneSix\Plugin\App\Action;

use Psr\Log\LoggerInterface;

class ActionDispatchBeforePlugin
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * ActionPluginExampleOne constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * Magento runs all before methods ahead of the call to an observed method.
     * These methods must have the same name as the observed method with ‘before’ as the prefix.
     *
     * You can use before methods to change the arguments of an observed method by returning a modified argument.
     * If there is more than one argument, the method should return an array of those arguments.
     * If the method does not change the argument for the observed method, it should return null.
     *
     * @see https://devdocs.magento.com/guides/v2.3/extension-dev-guide/plugins.html#before-methods
     *
     * @param \Magento\Framework\App\Action\Action    $subject
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return null
     *
     * @see \Magento\Framework\App\Action\Action::dispatch
     */
    public function beforeDispatch(
        \Magento\Framework\App\Action\Action $subject,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->logger->debug(__METHOD__ . '::11');

        return null;
    }
}
