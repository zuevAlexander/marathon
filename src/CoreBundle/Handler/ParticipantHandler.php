<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\Participant;
use CoreBundle\Model\Request\Participant\ParticipantCreateRequest;
use CoreBundle\Model\Request\Participant\ParticipantReadRequest;
use CoreBundle\Model\Handler\ParticipantProcessorInterface;
use CoreBundle\Model\Request\Participant\ParticipantDeleteRequest;
use CoreBundle\Service\Participant\ParticipantService;

/**
 * Class ParticipantHandler
 * @package CoreBundle\Handler
 */
class ParticipantHandler implements ParticipantProcessorInterface
{
    /**
     * @var ParticipantService
     */
    private $participantService;

    /**
     * ParticipantHandler constructor.
     * @param ParticipantService $participantService
     */
    public function __construct(ParticipantService $participantService) {
        $this->participantService = $participantService;
    }

    /**
     * @param ParticipantCreateRequest $request
     * @return Participant
     */
    public function processPost(ParticipantCreateRequest $request) : Participant
    {
        return $this->participantService->createParticipant($request);
    }

    /**
     * @param ParticipantReadRequest $request
     * @return Participant
     */
    public function processGet(ParticipantReadRequest $request): Participant
    {
        return $this->participantService->getParticipant($request);
    }

    /**
     * @param ParticipantDeleteRequest $request
     * @return Participant
     */
    public function processDelete(ParticipantDeleteRequest $request): Participant
    {
        return $this->participantService->deleteEntity($request->getParticipant());
    }
}
