<?php
namespace Frontend\Controller\Gallery;

use Frontend\Data\Gallery\CollageData;
use Frontend\Service\CollageService;
use Frontend\Service\Instagram\AuthenticationService;
use Frontend\Service\Instagram\Images\CollectionFactory;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\ViewModel;

class CollageController extends AbstractController
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory = null;

    /**
     * @var CollageData
     */
    protected $collageData = null;

    /**
     * @var SessionContainer
     */
    protected $sessionContainer = null;

    /**
     * @var CollageService
     */
    protected $collageService = null;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService = null;

    public function __construct(CollectionFactory $collectionFactory,
                                SessionContainer $sessionContainer,
                                CollageService $collageService,
                                AuthenticationService $authenticationService,
                                CollageData $collageData)
    {
        $this->collectionFactory = $collectionFactory;
        $this->sessionContainer = $sessionContainer;
        $this->collageData = $collageData;
        $this->authenticationService = $authenticationService;
        $this->collageService = $collageService;
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        $valid = $this->collageData->isValid();
        $data = $this->authenticationService->getAuthData();
        $viewModel->setVariable('validInputs', $this->collageData->getValidInput());
        $viewModel->setVariable('user', $data->user);

        if (!$valid) {
            $viewModel->setTemplate('frontend/gallery/error');
            $viewModel->setVariable('messages', $this->collageData->getMessages());
            return $e->setResult($viewModel);
        }

        if ($this->sessionContainer->valuesHash != md5(serialize($this->collageData->getValues()))) {
            $collectionService = $this->collectionFactory->createCollection( $this->collageData );
            $images = $collectionService->getImages($this->collageData);
        } else {
            $images = $this->sessionContainer->images;
        }
        
        $collageContent = $this->collageService->create($this->collageData->getWidth(),
                                                        $this->collageData->getHeight(),
                                                        $images);

        if (!is_null($collageContent)) {
            $response = $this->getResponse();

            $response->setContent( $collageContent );
            $response
                ->getHeaders()
                ->addHeaderLine( 'Content-Transfer-Encoding', 'binary' )
                ->addHeaderLine( 'Content-Type', 'image/png' )
                ->addHeaderLine( 'Content-Length', mb_strlen( $collageContent ) );

            return $response;
        } else {
            $viewModel->setTemplate('frontend/gallery/index');
            $e->setResult($viewModel);
        }
    }
}
