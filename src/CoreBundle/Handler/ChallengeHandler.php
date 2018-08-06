<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\Challenge;
use CoreBundle\Model\Request\Challenge\ChallengeListRequest;
use CoreBundle\Model\Request\Challenge\ChallengeCreateRequest;
use CoreBundle\Model\Request\Challenge\ChallengeReadRequest;
use CoreBundle\Model\Handler\ChallengeProcessorInterface;
use CoreBundle\Model\Request\Challenge\ChallengeUpdateRequest;
use CoreBundle\Model\Request\Challenge\ChallengeDeleteRequest;
use CoreBundle\Service\Challenge\ChallengeService;

/**
 * Class ChallengeHandler
 * @package CoreBundle\Handler
 */
class ChallengeHandler implements ChallengeProcessorInterface
{
    /**
     * @var ChallengeService
     */
    private $challengeService;

    /**
     * ChallengeHandler constructor.
     * @param ChallengeService $challengeService
     */
    public function __construct(ChallengeService $challengeService) {
        $this->challengeService = $challengeService;
    }

    /**
     * @param ChallengeCreateRequest $request
     * @return Challenge
     */
    public function processPost(ChallengeCreateRequest $request) : Challenge
    {
        return $this->challengeService->createChallenge($request);
    }

    /**
     * @param ChallengeReadRequest $request
     * @return Challenge
     */
    public function processGet(ChallengeReadRequest $request): Challenge
    {
        return $this->challengeService->getChallenge($request);
    }

    /**
     * @param ChallengeListRequest $request
     * @return array
     */
    public function processGetC(ChallengeListRequest $request): array
    {
        return $this->challengeService->getChallenges($request);
    }

    /**
     * @param ChallengeUpdateRequest $request
     * @return Challenge
     */
    public function processPatch(ChallengeUpdateRequest $request): Challenge
    {
        return $this->challengeService->updateChallenge($request);
    }

    /**
     * @param ChallengeDeleteRequest $request
     * @return Challenge
     */
    public function processDelete(ChallengeDeleteRequest $request): Challenge
    {
        return $this->challengeService->deleteEntity($request->getChallenge());
    }
}
