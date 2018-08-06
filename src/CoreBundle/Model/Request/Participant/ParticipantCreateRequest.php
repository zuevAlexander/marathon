<?php

namespace CoreBundle\Model\Request\Participant;

use CoreBundle\Entity\User;
use CoreBundle\Entity\Challenge;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ParticipantCreateRequest
 * @package CoreBundle\Model\Request\Participant
 */
class ParticipantCreateRequest
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Challenge
     */
    private $challenge;

    /**
     * ParticipantCreateRequest constructor.
     */
    public function __construct()
    {
        $this->user = new User();
        $this->challenge = new Challenge();
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
     * @return Challenge
     */
    public function getChallenge(): Challenge
    {
        return $this->challenge;
    }

    /**
     * @param Challenge $challenge
     */
    public function setChallenge(Challenge $challenge)
    {
        $this->challenge = $challenge;
    }
}
