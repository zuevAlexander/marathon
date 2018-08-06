<?php

namespace CoreBundle\Model\Request\Challenge;

use CoreBundle\Entity\Status;

/**
 * Interface ChallengeValidationInterface
 * @package CoreBundle\Model\Request\Challenge
 */
interface ChallengeValidationInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getAuthor(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return int
     */
    public function getStartDate(): int;

    /**
     * @return int
     */
    public function getEndDate(): int;

    /**
     * @return int
     */
    public function getDailyGoal(): int;

    /**
     * @return int
     */
    public function getChallengeGoal(): int;

    /**
     * @return Status
     */
    public function getStatus(): Status;
}