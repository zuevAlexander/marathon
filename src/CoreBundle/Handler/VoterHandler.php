<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\Voter;
use CoreBundle\Model\Request\Voter\VoterCreateRequest;
use CoreBundle\Service\Voter\VoterService;
use RestBundle\Handler\ProcessorInterface;

/**
 * Class VoterHandler
 */
class VoterHandler implements ProcessorInterface
{
    /**
     * @var VoterService
     */
    private $voterService;

    /**
     * VoterHandler constructor.
     * @param VoterService $voterService
     */
    public function __construct(VoterService $voterService) {
        $this->voterService = $voterService;
    }

    /**
     * @param VoterCreateRequest $request
     * @return Voter
     */
    public function processPost(VoterCreateRequest $request) : Voter
    {
        return $this->voterService->createVoter($request);
    }

}
