<?php

namespace M2StudyGroupUnit1\PluginsOrder\Plugin\Magento\Framework\App\Action\Action;

use M2StudyGroupUnit1\PluginsOrder\Plugin\BasePlugin;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;

class Around12 extends BasePlugin
{
    /**
     * @param Action           $action
     * @param callable         $proceed
     * @param RequestInterface $request
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function aroundDispatch(Action $action, callable $proceed, RequestInterface $request)
    {
        /*before part*/
        $this->sayHello([$action, $proceed], 'its before part');
        $returnValue = $proceed($request);
        /*after part*/
        $this->sayHello([$action, $proceed], 'its after part');

        return $returnValue;
    }
}
