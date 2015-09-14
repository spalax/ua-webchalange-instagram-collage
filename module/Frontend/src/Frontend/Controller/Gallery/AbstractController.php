<?php
namespace Frontend\Controller\Gallery;

use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\Controller\AbstractController as ZendAbstractController;

abstract class AbstractController extends ZendAbstractController
{
    /* (non-PHPdoc)
     * @see \Zend\Mvc\Controller\AbstractController::dispatch()
     */
    public function dispatch(Request $request, Response $response = null)
    {
        $authenticationService = $this->serviceLocator->get('di')
                                                     ->get('Frontend\Service\Instagram\AuthenticationService');

        if (is_null($authenticationService->getAuthData())) {
            return $this->redirect()->toRoute( 'frontend' );
        }

        $this->serviceLocator->get('di')->get('Frontend\Service\Instagram\AuthorizationService')->authorize();
        return parent::dispatch($request, $response);
    }

    /* (non-PHPdoc)
    * @see \Zend\Mvc\Controller\AbstractController::attachDefaultListeners()
    */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();

        $this->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function (MvcEvent $event) {
            $event->getTarget()->layout('layout/frontend/gallery');
        });
    }
}
