<?php

namespace CoreBundle\Model\Request\User;

use RestBundle\Request\AbstractRequest;
use CoreBundle\Entity\User;

/**
 * Class UserUpdateRequest
 */
class UserUpdateRequest extends AbstractRequest
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $username = '';

    /**
     * @var string
     */
    private $email = '';

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserUpdateRequest
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }
}
