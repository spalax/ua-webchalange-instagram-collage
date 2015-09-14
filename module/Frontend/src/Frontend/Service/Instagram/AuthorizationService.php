<?php
namespace Frontend\Service\Instagram;

use Frontend\Service\Instagram\Exception\BadAuthenticationException;
use Frontend\Wrapper\API\InstagramWrapperInterface;
use Zend\Session\Container as SessionContainer;

class AuthorizationService
{
    /**
     * @var SessionContainer
     */
    protected $sessionContainer = null;

    /**
     * @var InstagramWrapperInterface
     */
    protected $instagramWrapper = null;

    /**
     * @param SessionContainer $sessionContainer
     * @param InstagramWrapperInterface $instagramWrapper
     */
    public function __construct(SessionContainer $sessionContainer, InstagramWrapperInterface $instagramWrapper)
    {
        $this->sessionContainer = $sessionContainer;
        $this->instagramWrapper = $instagramWrapper;
    }

    /**
     * @throws BadAuthenticationException
     */
    public function authorize()
    {
        if (!$this->sessionContainer->offsetExists('data')) {
            throw new BadAuthenticationException('You must be first authenticated');
        }

        $this->instagramWrapper->setAccessToken($this->sessionContainer->data);
    }
}
