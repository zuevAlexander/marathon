<?php

namespace CoreBundle\Model\Request\User;

use CoreBundle\Entity\User;
use RestBundle\Request\AbstractRequest;

/**
 * Class UserDeleteRequest
 */
class UserDeleteRequest extends AbstractRequest
{
    /**
     * @var User
     */
    private $user;

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
     * @return UserDeleteRequest
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
