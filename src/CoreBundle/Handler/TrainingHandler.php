<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\Training;
use CoreBundle\Model\Request\Training\TrainingCreateRequest;
use CoreBundle\Model\Request\Training\TrainingDeleteRequest;
use CoreBundle\Model\Request\Training\TrainingUpdateRequest;
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
    public function __construct(TrainingService $trainingService)
    {
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

    /**
     * @param TrainingUpdateRequest $request
     * @return Training
     */
    public function processPatch(TrainingUpdateRequest $request): Training
    {
        return $this->trainingService->updatePatch($request);
    }

    /**
     * @param TrainingDeleteRequest $request
     * @return Training
     */
    public function processDelete(TrainingDeleteRequest $request): Training
    {
        return $this->trainingService->deleteEntity($request->getTraining());
    }
}
