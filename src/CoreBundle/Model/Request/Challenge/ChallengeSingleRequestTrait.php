<?php

namespace CoreBundle\Model\Request\Challenge;

use CoreBundle\Entity\Challenge;

trait ChallengeSingleRequestTrait
{
    /**
     * @var Challenge
     */
    private $challenge;

    /**
     * ChallengeSingleRequestTrait constructor.
     */
    public function __construct()
    {
        $this->challenge = new Challenge();
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
