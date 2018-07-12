<?php

namespace CoreBundle\Model\Handler;

use CoreBundle\Entity\Training;
use CoreBundle\Model\Request\Training\TrainingCreateRequest;
use RestBundle\Handler\ProcessorInterface;

/**
 * Interface TrainingProcessorInterface
 */
interface TrainingProcessorInterface extends ProcessorInterface
{
    /**
     * @param TrainingCreateRequest $request
     *
     * @return Training
     */
    public function processPost(TrainingCreateRequest $request) : Training;
}
