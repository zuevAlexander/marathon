<?php
namespace CoreBundle\Service\Training;

use CoreBundle\Entity\Training;
use CoreBundle\Model\Request\Training\TrainingCreateRequest;
use CoreBundle\Model\Request\Training\TrainingUpdateRequest;
use CoreBundle\Service\Day\DayService;
use CoreBundle\Service\User\UserService;
use RestBundle\Entity\EntityInterface;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserService
 *
 * @method Training createEntity()
 * @method Training deleteEntity(EntityInterface $entity, bool $flush = true)
 */
class TrainingService extends AbstractService
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var DayService
     */
    private $dayService;

    /**
     * TrainingService constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     * @param UserService $userService
     * @param DayService $dayService
     */
    public function __construct(ContainerInterface $container, string $entityClass, UserService $userService, DayService $dayService
    ) {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
        $this->userService = $userService;
        $this->dayService = $dayService;
    }

    /**
     * @param TrainingCreateRequest $request
     * @return Training
     */
    public function createTraining(TrainingCreateRequest $request): Training
    {
        $day = $this->dayService->getEntityBy(
            [
                'dayNumber' => $request->getDay(),
                'user' => $request->getUser()
            ]
        );

        $training = $this->createEntity();
        $training->setDay($day);
        $training->setDate(new \DateTime());
        $training->setAmount($request->getAmount());
        $this->saveEntity($training);

        return $training;
    }

    /**
     * @param TrainingUpdateRequest $request
     * @return Training
     */
    public function updatePatch(TrainingUpdateRequest $request): Training
    {
        $training = $request->getTraining();

        if ($request->getTraining()) {
            $training->setAmount($request->getAmount());
            $this->saveEntity($training);
        }

        return $training;
    }
}