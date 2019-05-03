<?php

namespace M2StudyGroupUnit1\PluginsOrder\Plugin\Plugin\Controller\Index\Index\Around2;

use M2StudyGroupUnit1\PluginsOrder\Controller\Index\Index;
use M2StudyGroupUnit1\PluginsOrder\Plugin\Controller\Index\Index\Around2;
use Magento\Framework\App\RequestInterface;

/**
 * Class Before
 *
 * @package \M2StudyGroupUnit1\PluginsOrder\Plugin\Plugin\Controller\Index\Index\Around2
 */
class BeforeAround2 extends \M2StudyGroupUnit1\PluginsOrder\Plugin\BasePlugin
{
    /**
     * @param Around2          $subject
     * @param Index            $action
     * @param callable         $proceed
     * @param RequestInterface $request
     * @param string           $test
     *
     * @throws \ReflectionException
     */
    public function beforeAroundDispatch(
        Around2 $subject,
        Index $action,
        callable $proceed,
        RequestInterface $request,
        $test = 'plugin BA2'
    ) {
        $this->sayHello([$subject, $action, $proceed, $request, $test]);
    }
}
