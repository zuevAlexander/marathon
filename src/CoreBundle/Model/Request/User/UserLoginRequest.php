<?php

namespace CoreBundle\Model\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserLoginRequest.
 */
class UserLoginRequest
{
    /**
     * @var string
     *
     */
    private $username = '';

    /**
     * @var string
     *
     */
    private $password = '';

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
