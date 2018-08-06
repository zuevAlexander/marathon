<?php

namespace CoreBundle\Model\Request\Participant;

use CoreBundle\Entity\Participant;

/**
 * Class ParticipantReadRequest
 * @package CoreBundle\Model\Request\Participant
 */
class ParticipantReadRequest
{
    /**
     * @var Participant
     */
    private $participant;

    /**
     * ParticipantCreateRequest constructor.
     */
    public function __construct()
    {
        $this->participant = new Participant();
    }

    /**
     * @return Participant
     */
    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    /**
     * @param Participant $participant
     */
    public function setParticipant(Participant $participant)
    {
        $this->participant = $participant;
    }
}
