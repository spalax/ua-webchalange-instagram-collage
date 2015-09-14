<?php
namespace Frontend\Options\Instagram;

interface  InstagramOptionsInterface
{
    /**
     * Return client id required
     * for connection to the instagram API
     *
     * @return string
     */
    public function getClientId();

    /**
     * Return client secret required
     * for connection to the instagram API
     *
     * @return string
     */
    public function getClientSecret();

    /**
     * Return success redirect uri
     *
     * @return string
     */
    public function getRedirectUri();
}
