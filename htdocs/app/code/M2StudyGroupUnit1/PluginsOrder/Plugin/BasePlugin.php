<?php

namespace M2StudyGroupUnit1\PluginsOrder\Plugin;

use Psr\Log\LoggerInterface;

class BasePlugin
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * BasePlugin constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array  $context
     * @param string $aroundPluginSuffix
     *
     * @throws \ReflectionException
     */
    public function sayHello(array $context, $aroundPluginSuffix = '')
    {
        $pluginName = (new \ReflectionClass($this))->getShortName();
        $message    = $pluginName . ' plugin say Hello ' . $aroundPluginSuffix;

        $this->logger->info($message, $context);
    }
}
