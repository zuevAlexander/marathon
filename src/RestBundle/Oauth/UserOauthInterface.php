<?php

namespace RestBundle\Oauth;

/**
 * Class UserOauthInterface.
 */
interface UserOauthInterface
{
    /**
     * @return string
     */
    public function getOauthProvider() : string;

    /**
     * @param string $oauthProvider
     *
     * @return UserOauthInterface
     */
    public function setOauthProvider(string $oauthProvider) : self;

    /**
     * @return string
     */
    public function getOauthAccessToken() : string;

    /**
     * @param string $oauthAccessToken
     *
     * @return UserOauthInterface
     */
    public function setOauthAccessToken(string $oauthAccessToken) : self;

    /**
     * @return string
     */
    public function getOauthId(): string;

    /**
     * @param string $oauthId
     *
     * @return UserOauthInterface
     */
    public function setOauthId(string $oauthId): self;
}
