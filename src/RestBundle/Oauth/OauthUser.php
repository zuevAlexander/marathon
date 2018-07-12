<?php

namespace RestBundle\Oauth;

class OauthUser
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $pictureUrl = '';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return OauthUser
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return OauthUser
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPictureUrl(): string
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $pictureUrl
     *
     * @return OauthUser
     */
    public function setPictureUrl(string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }
}
