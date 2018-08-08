<?php

namespace CoreBundle\Model\Request\Vote;

use CoreBundle\Entity\Challenge;
use RestBundle\Request\AbstractRequest;
use RestBundle\Request\ListRequestInterface;
use RestBundle\Request\ListRequestTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VoteListRequest
 * @package CoreBundle\Model\Request\Vote
 */
class VoteListRequest extends AbstractRequest implements ListRequestInterface
{
    use ListRequestTrait;
    /**
     * @var Challenge
     */
    private $challenge;

    /**
     * TrainingCreateRequest constructor.
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
     * @return VoteListRequest
     */
    public function setChallenge(Challenge $challenge)
    {
        $this->challenge = $challenge;

        return $this;
    }
}
