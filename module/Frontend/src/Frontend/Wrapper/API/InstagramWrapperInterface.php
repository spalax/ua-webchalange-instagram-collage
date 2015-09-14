<?php
namespace Frontend\Wrapper\API;

interface InstagramWrapperInterface
{

    /**
     * @param string [optional] $user
     * @param int [optional] $limit
     *
     * @return array | null
     */
    public function getUserMedia($user = 'self', $limit = 5);

    /**
     * @param integer [optional] $limit
     *
     * @throws InstagramException
     * @return array | null
     */
    public function getUserFeed($limit = 5);

    /**
     * @param object $items
     *
     * @return object
     */
    public function pagination($items, $limit);

    /**
     * @param $code
     * @param bool [optional] $token
     *
     * @return mixed
     */
    public function getOAuthToken( $code, $token = false );

    /**
     * @param array $data
     */
    public function setAccessToken($data);

    /**
     * @return string
     */
    public function getAccessToken();

    /**
     * @return string
     */
    public function getLoginUrl();
}
