<?php

namespace CoreBundle\Model\Request\Challenge;

use CoreBundle\Entity\Challenge;
use RestBundle\Request\AbstractRequest;

/**
 * Class ChallengeDeleteRequest
 * @package CoreBundle\Model\Request\Challenge
 */
class ChallengeDeleteRequest extends AbstractRequest
{
    /**
     * @var Challenge
     */
    private $challenge;

    /**
     * @inheritDoc
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
     * @return ChallengeDeleteRequest
     */
    public function setChallenge(Challenge $challenge)
    {
        $this->challenge = $challenge;

        return $this;
    }
}
