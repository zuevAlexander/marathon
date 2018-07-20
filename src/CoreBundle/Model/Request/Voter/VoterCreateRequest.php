<?php

namespace CoreBundle\Model\Request\Voter;

use CoreBundle\Entity\User;
use CoreBundle\Entity\Vote;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VoterCreateRequest.
 */
class VoterCreateRequest
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     */
    protected $votingName = '';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    protected $votingEmail = '';

    /**
     * @var ArrayCollection
     */
    protected $votes;

    /**
     * TrainingCreateRequest constructor.
     */
    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getVotingName(): string
    {
        return $this->votingName;
    }

    /**
     * @param string $votingName
     */
    public function setVotingName(string $votingName)
    {
        $this->votingName = $votingName;
    }

    /**
     * @return string
     */
    public function getVotingEmail(): string
    {
        return $this->votingEmail;
    }

    /**
     * @param string $votingEmail
     */
    public function setVotingEmail(string $votingEmail)
    {
        $this->votingEmail = $votingEmail;
    }

    /**
     * @return ArrayCollection|Vote[]
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param $votes
     * @return VoterCreateRequest
     */
    public function setVotes($votes): self
    {
        $this->votes = $votes;

        return $this;
    }
}
