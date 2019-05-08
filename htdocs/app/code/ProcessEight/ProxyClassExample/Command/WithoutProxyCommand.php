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
 * Class WithoutProxyCommand
 *
 * Demonstration of the problem which Proxy Classes can fix
 *
 * @package ProcessEight\ProxyClassExample\Command
 */
class WithoutProxyCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \ProcessEight\ProxyClassExample\Model\FastLoading
     */
    private $fastLoading;

    /**
     * WithoutProxyCommand constructor.
     *
     * @param \ProcessEight\ProxyClassExample\Model\FastLoading $fastLoading
     */
    public function __construct(
        \ProcessEight\ProxyClassExample\Model\FastLoading $fastLoading
    ) {
        parent::__construct();

        $this->fastLoading = $fastLoading;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName("process-eight:example:proxy-classes:without-proxy");
        $this->setDescription("Demonstrates how a resource-intensive dependency can slow down object instantiation");
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
        $output->writeln("You should have experienced a ten second delay before seeing this message.");
        $output->writeln("That's because this command defines <info>\ProcessEight\ProxyClassExample\Model\FastLoading</info> as a dependency, but <info>FastLoading</info> defines <info>\ProcessEight\ProxyClassExample\Model\SlowLoading</info> as a dependency.");
        $output->writeln("So the five-second penalty of instantiating <info>SlowLoading</info> is incurred even if that dependency isn't actually used in this request, command, etc.");
        $output->writeln("The solution to this is to use Proxy Classes.");
        $output->writeln("Try running the <info>process-eight:example:proxy-classes:with-proxy</info> command. It should be much quicker.");
        $output->writeln("<info>NOTE: </info> If you run the <info>process-eight:example:proxy-classes:with-proxy</info> command without disabling the <info>process-eight:example:proxy-classes:without-proxy</info> command, you won't notice any performance difference. This is because the Symfony Console Component initialises all commands as part of it's bootstrapping procedure, and thus loads the <info>SlowLoading</info> class as part of the initialisation of the <info>process-eight:example:proxy-classes:without-proxy</info> command. To avoid this, you will need to comment out the <info>process-eight:example:proxy-classes:without-proxy</info> command in <info>di.xml</info>, clear cache and then run the <info>process-eight:example:proxy-classes:with-proxy</info> command.");

        return 0;
    }

}
