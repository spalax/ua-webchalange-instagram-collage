<?php
namespace Frontend\Controller\Login;

use Frontend\Controller\AbstractController;
use Frontend\Service\Instagram\AuthenticationService;
use Frontend\Service\Instagram\AuthorizationService;
use Frontend\Service\Instagram\Exception\InvalidCodeException;
use Frontend\Validator\Instagram\CodeValidator;
use Frontend\Exception\DomainException;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class SuccessController extends AbstractController
{
    /**
     * @var CodeValidator
     */
    protected $validator = null;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService = null;

    /**
     * @param AuthenticationService $authService
     * @param AuthorizationService $authorizationService
     * @param CodeValidator $validator
     */
    public function __construct(AuthenticationService $authenticationService,
                                AuthorizationService $authorizationService,
                                CodeValidator $validator)
    {
        $this->validator = $validator;
        $this->authenticationService = $authenticationService;

        parent::__construct($authorizationService);
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        try {
            $this->authenticationService->authenticate($this->params()->fromQuery( 'code', null ));
            return $this->redirect()->toRoute('frontend/gallery/configure');
        } catch ( InvalidCodeException $invalidCodeException ) {
            $viewModel->setTemplate( 'frontend/error/unrecoverable' );
            $viewModel->setVariable( 'errorMessage', 'Incorrect code string returned from Instagram' );
        } catch ( DomainException $domainException) {
            $viewModel->setTemplate( 'frontend/error/unrecoverable' );
        }

        $e->setResult($viewModel);
    }
}
