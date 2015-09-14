<?php
namespace Frontend\Controller;

use Frontend\Service\Instagram\AuthorizationService;
use Frontend\Wrapper\API\InstagramWrapperInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    /**
     * @var InstagramWrapperInterface
     */
    protected $instagramWrapper = null;

    /**
     * @var AuthorizationService|null
     */
    protected $authorizationService = null;

    /**
     * @param InstagramWrapperInterface $instagramWrapper
     * @param AuthorizationService $authorizationService
     */
    public function __construct(InstagramWrapperInterface $instagramWrapper,
                                AuthorizationService $authorizationService)
    {
        $this->instagramWrapper = $instagramWrapper;
        parent::__construct($authorizationService);
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        $viewModel->setVariable( 'loginUri', $this->instagramWrapper->getLoginUrl() );
        $viewModel->setTemplate( 'frontend/index' );

        $e->setResult($viewModel);
    }
}
