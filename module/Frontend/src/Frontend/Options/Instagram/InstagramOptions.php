<?php
namespace Frontend\Options\Instagram;

use Frontend\Options\Instagram\Exception\InvalidOptionException;
use Zend\Stdlib\AbstractOptions;

class InstagramOptions extends AbstractOptions implements InstagramOptionsInterface
{
    /**
     * @var string
     */
    protected $clientId = '';

    /**
     * @var string
     */
    protected $clientSecret = '';

    /**
     * @var string
     */
    protected $redirectUri = '';

    /**
     * @param string $clientId
     * @throws InvalidOptionException
     */
    protected function setClientId($clientId)
    {
        if (strlen(trim($clientId)) == 1) {
            throw new InvalidOptionException("Api Key should be defined and must not be empty");
        }

        $this->clientId = $clientId;
    }

    /**
     * @param string $clientSecret
     * @throws InvalidOptionException
     */
    protected function setClientSecret($clientSecret)
    {
        if (strlen(trim($clientSecret)) == 1) {
            throw new InvalidOptionException("Api secret should be defined and must not be empty");
        }

        $this->clientSecret = $clientSecret;
    }

    /**
     * @param string $redirectUri
     * @throws InvalidOptionException
     */
    protected function setRedirectUri($redirectUri)
    {
        if (strlen(trim($redirectUri)) == 1) {
            throw new InvalidOptionException("Redirect uri should be defined and must not be empty");
        }

        $this->redirectUri = $redirectUri;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
}
