<?php
namespace Frontend\Controller\Gallery;

use Frontend\Service\Instagram\AuthenticationService;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class ConfigureController extends AbstractController
{
    /**
     * @var AuthenticationService
     */
    protected $authenticationService = null;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory = null;


    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        $data = $this->authenticationService->getAuthData();
        $viewModel->setVariable('user', $data->user);
        $viewModel->setVariable('configure', true);

        $viewModel->setTemplate( 'frontend/gallery/index' );
        $e->setResult($viewModel);
    }
}
