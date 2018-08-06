<?php

namespace CoreBundle\Model\Request\Challenge;

use CoreBundle\Entity\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChallengeCreateRequest
 * @package CoreBundle\Model\Request\Challenge
 */
class ChallengeCreateRequest implements ChallengeValidationInterface
{
    use ChallengeValidationTrait;

    /**
     * ChallengeCreateRequest constructor.
     */
    public function __construct()
    {
        $this->status = new Status();
        $this->participants = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return (string)$this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return (string)$this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return int
     */
    public function getStartDate(): int
    {
        return (int)$this->startDate;
    }

    /**
     * @param int $startDate
     */
    public function setStartDate(int $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return int
     */
    public function getEndDate(): int
    {
        return (int)$this->endDate;
    }

    /**
     * @param int $endDate
     */
    public function setEndDate(int $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return int
     */
    public function getDailyGoal(): int
    {
        return (int)$this->dailyGoal;
    }

    /**
     * @param int $dailyGoal
     */
    public function setDailyGoal(int $dailyGoal)
    {
        $this->dailyGoal = $dailyGoal;
    }

    /**
     * @return int
     */
    public function getChallengeGoal(): int
    {
        return (int)$this->challengeGoal;
    }

    /**
     * @param int $challengeGoal
     */
    public function setChallengeGoal(int $challengeGoal)
    {
        $this->challengeGoal = $challengeGoal;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status)
    {
        $this->status = $status;
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param $participants
     * @return ChallengeCreateRequest
     */
    public function setParticipants($participants): self
    {
        $this->participants = $participants;

        return $this;
    }
}
