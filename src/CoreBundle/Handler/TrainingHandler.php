<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\Training;
use CoreBundle\Model\Request\Training\TrainingCreateRequest;
use CoreBundle\Service\Training\TrainingService;
use CoreBundle\Model\Handler\TrainingProcessorInterface;

/**
 * Class TrainingHandler
 */
class TrainingHandler implements TrainingProcessorInterface
{
    /**
     * @var TrainingService
     */
    private $trainingService;

    /**
     * UserHandler constructor.
     * @param TrainingService $trainingService
     */
    public function __construct(TrainingService $trainingService) {
        $this->trainingService = $trainingService;
    }

    /**
     * @param TrainingCreateRequest $request
     *
     * @return Training
     */
    public function processPost(TrainingCreateRequest $request) : Training
    {
        return $this->trainingService->createTraining($request);
    }
}
