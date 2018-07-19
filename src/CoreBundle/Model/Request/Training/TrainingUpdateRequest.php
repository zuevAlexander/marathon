<?php

namespace CoreBundle\Model\Request\Training;

use RestBundle\Request\AbstractRequest;
use CoreBundle\Entity\Training;

/**
 * Class TrainingUpdateRequest
 */
class TrainingUpdateRequest extends AbstractRequest
{
    /**
     * @var Training
     */
    private $training;

    /**
     * @var int
     */
    private $amount = 0;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->training = new Training();
    }

    /**
     * @return Training
     */
    public function getTraining() : Training
    {
        return $this->training;
    }

    /**
     * @param Training $training
     * @return TrainingUpdateRequest
     */
    public function setTraining(Training $training)
    {
        $this->training = $training;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount() : int
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
}