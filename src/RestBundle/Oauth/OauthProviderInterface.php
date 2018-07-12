<?php

namespace RestBundle\Oauth;

interface OauthProviderInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $accessToken
     * @param mixed  $errorMessage
     *
     * @return mixed oauth identifier if exists or false otherwise
     */
    public function authenticate(string $accessToken, &$errorMessage): OauthUser;
}
