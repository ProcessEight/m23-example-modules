<?php

namespace M2StudyGroupUnit1\PluginsOrder\Plugin\Magento\Framework\App\Action\Action;

use M2StudyGroupUnit1\PluginsOrder\Plugin\BasePlugin;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface;

class After10 extends BasePlugin
{
    /**
     * @param Action           $action
     * @param ResultInterface  $result
     * @param RequestInterface $request
     *
     * @return ResultInterface
     * @throws \ReflectionException
     */
    public function afterDispatch(Action $action, ResultInterface $result, RequestInterface $request)
    {
        $this->sayHello([$action, $result, $request]);

        return $result;
    }
}
