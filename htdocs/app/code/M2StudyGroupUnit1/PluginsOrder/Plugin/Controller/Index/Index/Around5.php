<?php

namespace M2StudyGroupUnit1\PluginsOrder\Plugin\Controller\Index\Index;

use M2StudyGroupUnit1\PluginsOrder\Controller\Index\Index;
use M2StudyGroupUnit1\PluginsOrder\Plugin\BasePlugin;
use Magento\Framework\App\RequestInterface;

class Around5 extends BasePlugin
{
    /**
     * @param Index            $action
     * @param callable         $proceed
     * @param RequestInterface $request
     * @param string           $test
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function aroundDispatch(Index $action, callable $proceed, RequestInterface $request, $test)
    {
        /*before part*/
        $this->sayHello([$action, $proceed, $test], 'its before part');
        $returnValue = $proceed($request, $test);
        /*after part*/
        $this->sayHello([$action, $proceed, $test], 'its after part');

        return $returnValue;
    }
}
