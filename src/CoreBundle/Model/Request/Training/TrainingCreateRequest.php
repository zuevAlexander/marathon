<?php

namespace CoreBundle\Model\Request\Training;

use CoreBundle\Entity\Day;
use CoreBundle\Entity\Participant;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TrainingCreateRequest.
 */
class TrainingCreateRequest
{

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    private $amount = 0;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    private $day;

    /**
     * @var Participant
     *
     * @Assert\NotBlank()
     */
    private $participant;

    /**
     * TrainingCreateRequest constructor.
     */
    public function __construct()
    {
        $this->days = new Day();
        $this->participant = new Participant();
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param int $day
     */
    public function setDay(int $day)
    {
        $this->day = $day;
    }

    /**
     * @return Participant
     */
    public function getParticipant()
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
