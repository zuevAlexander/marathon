<?php

namespace CoreBundle\Model\Request\Training;

use CoreBundle\Entity\Day;
use CoreBundle\Entity\User;
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
     * @var User
     *
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * TrainingCreateRequest constructor.
     */
    public function __construct()
    {
        $this->days = new Day();
        $this->user = new User();
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

}
