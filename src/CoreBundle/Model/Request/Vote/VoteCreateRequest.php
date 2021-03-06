<?php

namespace CoreBundle\Model\Request\Vote;

use CoreBundle\Entity\Challenge;
use CoreBundle\Entity\Rating;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VoteCreateRequest.
 */
class VoteCreateRequest
{
    /**
     * @var Challenge
     */
    private $challenge;

    /**
     * @var ArrayCollection
     */
    private $ratings;

    /**
     * TrainingCreateRequest constructor.
     */
    public function __construct()
    {
        $this->challenge = new Challenge();
        $this->ratings = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|Rating[]
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param $ratings
     * @return VoteCreateRequest
     */
    public function setRatings($ratings): self
    {
        $this->ratings = $ratings;

        return $this;
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
     * @return VoteCreateRequest
     */
    public function setChallenge(Challenge $challenge)
    {
        $this->challenge = $challenge;

        return $this;
    }
}
