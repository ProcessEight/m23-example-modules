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

class ActionDispatchAfterPlugin
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
     * Magento runs all after methods following the completion of the observed method.
     * Magento requires these methods have a return value and they must have the same name as the observed method with ‘after’ as the prefix.
     *
     * You can use these methods to change the result of an observed method by modifying the original result and returning it at the end of the method.
     *
     * After methods have access to all the arguments of their observed methods.
     * When the observed method completes, Magento passes the result and arguments to the next after method that follows.
     * If the observed method does not return a result (`@return void`), then it passes null to the next after method.
     *
     * If an argument is optional in the observed method, then the after method should also declare it as optional.
     *
     * If an argument from the observed method is not used in the plugin, it should not be included in the type signature of the plugin.
     *
     * @see https://devdocs.magento.com/guides/v2.3/extension-dev-guide/plugins.html#after-methods
     *
     * @param \Magento\Framework\App\Action\Action $subject
     * @param                                      $result
     *
     * @return mixed
     *
     * @see \Magento\Framework\App\Action\Action::dispatch
     *
     */
    public function afterDispatch(\Magento\Framework\App\Action\Action $subject, $result)
    {
        $this->logger->debug(__METHOD__ . '::13');

        return $result;
    }
}
