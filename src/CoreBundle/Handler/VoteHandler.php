<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\Vote;
use CoreBundle\Model\Request\Vote\VoteCreateRequest;
use CoreBundle\Model\Request\Vote\VoteListRequest;
use CoreBundle\Service\Vote\VoteService;
use Doctrine\Common\Collections\Collection;
use RestBundle\Handler\ProcessorInterface;

/**
 * Class VoteHandler
 */
class VoteHandler implements ProcessorInterface
{
    /**
     * @var VoteService
     */
    private $voteService;

    /**
     * VoterHandler constructor.
     * @param VoteService $voteService
     */
    public function __construct(VoteService $voteService) {
        $this->voteService = $voteService;
    }

    /**
     * @param VoteListRequest $request
     * @return Collection
     */
    public function processGetC(VoteListRequest $request) : Collection
    {
        return $this->voteService->getVotes($request);
    }

    /**
     * @param VoteCreateRequest $request
     * @return Vote
     */
    public function processPost(VoteCreateRequest $request) : Vote
    {
        return $this->voteService->createVote($request);
    }

}
