<?php

namespace CoreBundle\Model\Handler;

use CoreBundle\Entity\Challenge;
use CoreBundle\Model\Request\Challenge\ChallengeReadRequest;
use CoreBundle\Model\Request\Challenge\ChallengeCreateRequest;
use RestBundle\Handler\ProcessorInterface;

/**
 * Interface ChallengeProcessorInterface
 * @package CoreBundle\Model\Handler
 */
interface ChallengeProcessorInterface extends ProcessorInterface
{
    /**
     * @param ChallengeCreateRequest $request
     * @return Challenge
     */
    public function processPost(ChallengeCreateRequest $request): Challenge;

    /**
     * @param ChallengeReadRequest $request
     * @return Challenge
     */
    public function processGet(ChallengeReadRequest $request): Challenge;
}
