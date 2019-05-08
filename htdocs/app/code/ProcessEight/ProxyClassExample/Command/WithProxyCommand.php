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

namespace ProcessEight\ProxyClassExample\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WithProxyCommand
 *
 * Demonstration of how to use Proxy Classes to avoid the performance problems of instantiating resource-intensive classes
 *
 * @package ProcessEight\ProxyClassExample\Command
 */
class WithProxyCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \ProcessEight\ProxyClassExample\Model\FastLoadingWithProxy
     */
    private $fastLoadingWithProxy;

    /**
     * WithProxyCommand constructor.
     *
     * @param \ProcessEight\ProxyClassExample\Model\FastLoadingWithProxy $fastLoadingWithProxy
     */
    public function __construct(
        \ProcessEight\ProxyClassExample\Model\FastLoadingWithProxy $fastLoadingWithProxy
    ) {
        parent::__construct();

        $this->fastLoadingWithProxy = $fastLoadingWithProxy;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:example:proxy-classes:with-proxy");
        $this->setDescription("Demonstrates how to use Proxy Classes to avoid the performance problems of instantiating resource-intensive classes");
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
        $output->writeln("You should NOT have experienced a ten second delay before seeing this message.");
        $output->writeln("<info>NOTE: </info> If you DID experience a ten-second delay, then try disabling the <info>process-eight:example:proxy-classes:without-proxy</info> command, otherwise you won't notice any performance difference. This is because the Symfony Console Component initialises all commands as part of it's bootstrapping procedure, and thus loads the <info>SlowLoading</info> class as part of the initialisation of the <info>process-eight:example:proxy-classes:without-proxy</info> command. To avoid this, you will need to comment out the <info>process-eight:example:proxy-classes:without-proxy</info> command in <info>di.xml</info>, clear cache and then run the <info>process-eight:example:proxy-classes:with-proxy</info> command.");

        return 0;
    }

}
