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

class ActionDispatchAroundPlugin
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
     * Magento runs the code in around methods before and after their observed methods.
     * Using these methods allow you to override an observed method.
     * Around methods must have the same name as the observed method with ‘around’ as the prefix.
     *
     * Avoid using around method plugins when they are not required because they increase stack traces and affect performance.
     * The only use case for around method plugins is when the execution of all further plugins and original methods need termination.
     * Use after method plugins if you require arguments for replacing or altering function results.
     *
     * Before the list of the original method’s arguments, around methods receive a callable that will allow a call to the next method in the chain.
     * When your code executes the callable, Magento calls the next plugin or the observed function.
     *
     * If the around method does not call the callable, it will prevent the execution of all the plugins next in the chain and the original method call.
     *
     * When you wrap a method which accepts arguments, your plugin must also accept those arguments and you must forward them when you invoke the `proceed` callable.
     * You must be careful to match the default parameters and type hints of the original signature of the method.
     *
     * @see https://devdocs.magento.com/guides/v2.3/extension-dev-guide/plugins.html#around-methods
     *
     * @param \Magento\Framework\App\Action\Action $subject
     * @param callable                             $proceed
     * @param array                                $arguments
     *
     * @return
     * @see \Magento\Framework\App\Action\Action::dispatch
     */
    public function aroundDispatch(
        \Magento\Framework\App\Action\Action $subject,
        callable $proceed,
        ...$arguments
    ) {
        $this->logger->debug(__METHOD__ . '::12::PRE::PROCEED');

        // ...$arguments is a shorthand for including all the parameters passed to the observed method
        // The technical name for it is 'variadic and argument unpacking'
        $returnValue = $proceed(...$arguments);

        $this->logger->debug(__METHOD__ . '::12::POST::PROCEED');

        return $returnValue;
    }
}
