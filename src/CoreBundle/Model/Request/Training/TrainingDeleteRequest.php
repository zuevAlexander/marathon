<?php

namespace CoreBundle\Model\Request\Training;

use CoreBundle\Entity\Training;
use RestBundle\Request\AbstractRequest;

/**
 * Class TrainingDeleteRequest
 */
class TrainingDeleteRequest extends AbstractRequest
{
    /**
     * @var Training
     */
    private $training;

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
    public function getTraining(): Training
    {
        return $this->training;
    }

    /**
     * @param Training $training
     * @return TrainingDeleteRequest
     */
    public function setTraining(Training $training)
    {
        $this->training = $training;

        return $this;
    }
}
