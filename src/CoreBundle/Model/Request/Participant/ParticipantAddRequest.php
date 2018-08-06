<?php

namespace CoreBundle\Model\Request\Participant;

use CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ParticipantAddRequest
 * @package CoreBundle\Model\Request\Participant
 */
class ParticipantAddRequest
{
    /**
     * @var User
     */
    private $user;

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
}
