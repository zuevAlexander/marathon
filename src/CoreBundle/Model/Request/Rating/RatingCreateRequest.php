<?php

namespace CoreBundle\Model\Request\Rating;

use CoreBundle\Entity\Participant;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RatingCreateRequest.
 */
class RatingCreateRequest
{
    /**
     * @var Participant
     */
    private $participant;

    /**
     * @var int
     */
    private $place = 0;

    /**
     * TrainingCreateRequest constructor.
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

    /**
     * @return int
     */
    public function getPlace(): int
    {
        return $this->place;
    }

    /**
     * @param int $place
     */
    public function setPlace(int $place)
    {
        $this->place = $place;
    }
}
