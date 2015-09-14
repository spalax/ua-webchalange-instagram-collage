<?php
namespace Frontend\Controller;

use Zend\Mvc\MvcEvent;
use Zend\Session\Container as SessionContainer;
use Zend\Mvc\Controller\AbstractController;

class LogoutController extends AbstractController
{
    /**
     * @var SessionContainer|null
     */
    protected $sessionContainer = null;

    /**
     * @param SessionContainer $sessionContainer
     */
    public function __construct(SessionContainer $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->sessionContainer->getDefaultManager()->forgetMe();
        $this->sessionContainer->getDefaultManager()->expireSessionCookie();
        $this->sessionContainer->getDefaultManager()->destroy();
        $this->redirect()->toRoute('frontend');
    }
}
