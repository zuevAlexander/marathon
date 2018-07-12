<?php

namespace RestBundle\Oauth;

use RestBundle\Exception\Oauth\UnknownOauthProviderException;
use RestBundle\Service\OauthProvider\FacebookProvider;

class OauthProviderFactory
{
    /**
     * @param string $provider
     *
     * @return OauthProviderInterface
     *
     * @throws UnknownOauthProviderException
     */
    public static function create(string $provider): OauthProviderInterface
    {
        switch ($provider) {
            case 'facebook':
                return new FacebookProvider();
            default:
                throw new UnknownOauthProviderException();
        }
    }
}
