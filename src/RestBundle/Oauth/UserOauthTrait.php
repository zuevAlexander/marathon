<?php

namespace RestBundle\Oauth;

trait UserOauthTrait
{
    /**
     * @var string
     */
    protected $oauthAccessToken;

    /**
     * @var string
     */
    protected $oauthProvider;

    /**
     * @var string
     */
    protected $oauthId;

    /**
     * @return string
     */
    public function getOauthProvider(): string
    {
        return (string) $this->oauthProvider;
    }

    /**
     * @param string $oauthProvider
     *
     * @return UserOauthInterface|self
     */
    public function setOauthProvider(string $oauthProvider): UserOauthInterface
    {
        $this->oauthProvider = $oauthProvider;

        return $this;
    }

    /**
     * @return string
     */
    public function getOauthAccessToken(): string
    {
        return (string) $this->oauthAccessToken;
    }

    /**
     * @param string $oauthAccessToken
     *
     * @return UserOauthInterface|self
     */
    public function setOauthAccessToken(string $oauthAccessToken): UserOauthInterface
    {
        $this->oauthAccessToken = $oauthAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getOauthId(): string
    {
        return $this->oauthId;
    }

    /**
     * @param string $oauthId
     *
     * @return UserOauthInterface|self
     */
    public function setOauthId(string $oauthId): UserOauthInterface
    {
        $this->oauthId = $oauthId;

        return $this;
    }
}
