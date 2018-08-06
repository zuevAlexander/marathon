<?php

namespace CoreBundle\Model\Handler;

use CoreBundle\Entity\Participant;
use CoreBundle\Model\Request\Participant\ParticipantReadRequest;
use CoreBundle\Model\Request\Participant\ParticipantCreateRequest;
use RestBundle\Handler\ProcessorInterface;

/**
 * Interface ParticipantProcessorInterface
 * @package CoreBundle\Model\Handler
 */
interface ParticipantProcessorInterface extends ProcessorInterface
{
    /**
     * @param ParticipantCreateRequest $request
     * @return Participant
     */
    public function processPost(ParticipantCreateRequest $request): Participant;

    /**
     * @param ParticipantReadRequest $request
     * @return Participant
     */
    public function processGet(ParticipantReadRequest $request): Participant;
}
