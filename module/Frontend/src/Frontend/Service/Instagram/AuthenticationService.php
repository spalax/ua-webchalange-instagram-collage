<?php
namespace Frontend\Service\Instagram;

use Frontend\Service\Exception\InvalidDataException;
use Frontend\Service\Instagram\Exception\BadAuthenticationException;
use Frontend\Service\Instagram\Exception\InvalidCodeException;
use Frontend\Validator\Instagram\CodeValidator;
use Frontend\Wrapper\API\InstagramException;
use Frontend\Wrapper\API\InstagramWrapperInterface;
use Zend\Session\Container as SessionContainer;

class AuthenticationService
{
    /**
     * @var SessionContainer
     */
    protected $sessionContainer = null;

    /**
     * @var CodeValidator
     */
    protected $codeValidator = null;

    /**
     * @var InstagramWrapperInterface
     */
    protected $instagramWrapper = null;

    /**
     * @param SessionContainer $sessionContainer
     * @param InstagramWrapperInterface $instagramWrapper
     * @param CodeValidator $codeValidator
     */
    public function __construct(SessionContainer $sessionContainer,
                                InstagramWrapperInterface $instagramWrapper,
                                CodeValidator $codeValidator)
    {
        $this->codeValidator = $codeValidator;
        $this->instagramWrapper = $instagramWrapper;
        $this->sessionContainer = $sessionContainer;
    }

    /**
     * @return object | null
     */
    public function getAuthData()
    {
        if (!$this->sessionContainer->offsetExists('data')) {
            return null;
        }

        return $this->sessionContainer->data;
    }

    /**
     * @param string $code
     * @throws InvalidDataException | BadAuthenticationException | InstagramException
     * @return bool
     */
    public function authenticate($code)
    {
        if (!$this->codeValidator->isValid($code)) {
            throw new InvalidCodeException('Invalid code param');
        }

        $data = $this->instagramWrapper->getOAuthToken($code);
        if (!empty($data->error_message)) {
            throw new BadAuthenticationException('Could not connect to the Instagram');
        }

        $this->sessionContainer->data = $data;
    }
}
