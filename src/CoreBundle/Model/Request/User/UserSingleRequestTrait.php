<?php

namespace CoreBundle\Model\Request\User;

trait UserSingleRequestTrait
{
    /**
     * @var int
     */
    private $user = 0;

    /**
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser(int $user)
    {
        $this->user = $user;
    }
}
