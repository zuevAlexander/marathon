<?php

namespace CoreBundle\Model\Request\Vote;

use CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VoteCreateRequest.
 */
class VoteCreateRequest
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var int
     */
    protected $place = 0;

    /**
     * TrainingCreateRequest constructor.
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
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getPlace(): int
    {
        return $this->place;
    }

    /**
     * @param int $place
     */
    public function setPlace(int $place)
    {
        $this->place = $place;
    }
}
