<?php

namespace M2StudyGroupUnit1\PluginsOrder\Plugin\Magento\Framework\App\Action\Action;

use M2StudyGroupUnit1\PluginsOrder\Plugin\BasePlugin;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;

class Before7 extends BasePlugin
{
    /**
     * @param Action           $action
     * @param RequestInterface $request
     *
     * @throws \ReflectionException
     */
    public function beforeDispatch(Action $action, RequestInterface $request)
    {
        $this->sayHello([$action, $request]);
    }
}
