<?php
namespace Frontend\Wrapper\API;

use Frontend\Options\Instagram\InstagramOptionsInterface;
use MetzWeb\Instagram\Instagram;

class InstagramWrapper implements InstagramWrapperInterface
{
    /**
     * @var InstagramOptionsInterface
     */
    protected $options = null;

    /**
     * @var Instagram
     */
    private $instance = null;

    /**
     * @param InstagramOptionsInterface $options
     * @param
     */
    public function __construct(InstagramOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * @return Instagram
     */
    protected function getLazyInstance()
    {
        if (is_null($this->instance)) {
            $this->instance = new Instagram([
                'apiKey' => $this->options->getClientId(),
                'apiSecret' => $this->options->getClientSecret(),
                'apiCallback' => $this->options->getRedirectUri()
            ]);
        }

        return $this->instance;
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        try {
            return $this->getLazyInstance()->getLoginUrl(['basic']);
        } catch (\Exception $ex) {
            throw new InstagramException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * @param string|int $nameOrId
     *
     * @return int | null
     */
    protected function getUserId($nameOrId)
    {
        if ($nameOrId == 'self' || is_numeric($nameOrId)) {
            return $nameOrId;
        }

        $userData = $this->getLazyInstance()
                         ->searchUser($nameOrId, 1);

        if (!empty($userData->data)) {
            return $userData->data[0]->id;
        }

        return null;
    }

    /**
     * @param string [optional] $user
     * @param int [optional] $limit
     *
     * @throws InstagramException
     * @return mixed
     */
    public function getUserMedia($user = 'self', $limit = 5)
    {
        try {
            return $this->getLazyInstance()->getUserMedia( $this->getUserId($user), $limit );
        } catch (\Exception $ex) {
            throw new InstagramException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * @param object $items
     * @param int $limit
     *
     * @throws InstagramException
     * @return array
     */
    public function pagination($items, $limit)
    {
        try {
            return $this->getLazyInstance()->pagination($items, $limit);
        } catch (\Exception $ex) {
            throw new InstagramException($ex->getMessage(), $ex->getCode(), $ex);
        }

    }

    /**
     * @param integer [optional] $limit
     *
     * @throws InstagramException
     * @return mixed
     */
    public function getUserFeed($limit = 5)
    {
        try {
            return $this->getLazyInstance()->getUserFeed($limit);
        } catch (\Exception $ex) {
            throw new InstagramException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * @param $code
     * @param bool|false $token
     * @throws InstagramException
     * @return mixed
     */
    public function getOAuthToken( $code, $token = false )
    {
        try {
            return $this->getLazyInstance()->getOAuthToken( $code, $token );
        } catch (\Exception $ex) {
            throw new InstagramException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * @param array $data
     */
    public function setAccessToken( $data )
    {
        $this->getLazyInstance()->setAccessToken( $data );
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return trim($this->getLazyInstance()->getAccessToken());
    }
}
