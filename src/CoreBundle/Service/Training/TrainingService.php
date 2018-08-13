<?php
namespace CoreBundle\Service\Training;

use CoreBundle\Entity\Training;
use CoreBundle\Entity\User;
use CoreBundle\Entity\Day;
use CoreBundle\Exception\User\BadCredentialsException;
use CoreBundle\Model\Request\Training\TrainingCreateRequest;
use CoreBundle\Model\Request\Training\TrainingUpdateRequest;
use CoreBundle\Service\Day\DayService;
use CoreBundle\Service\CurrentUser\CurrentUserService;
use CoreBundle\Service\Participant\ParticipantService;
use RestBundle\Entity\EntityInterface;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserService
 *
 * @method Training createEntity()
 * @method Training deleteEntity(EntityInterface $entity, bool $flush = true)
 */
class TrainingService extends AbstractService
{
    /**
     * @var ParticipantService
     */
    private $participantService;

    /**
     * @var DayService
     */
    private $dayService;

    /**
     * @var User
     */
    private  $currentUser;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * TrainingService constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     * @param ParticipantService $participantService
     * @param DayService $dayService
     * @param CurrentUserService $currentUserService
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(ContainerInterface $container, string $entityClass, ParticipantService $participantService, DayService $dayService, CurrentUserService $currentUserService, AuthorizationCheckerInterface $authChecker)
    {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
        $this->participantService = $participantService;
        $this->dayService = $dayService;
        $this->currentUser = $currentUserService->getCurrentUser();
        $this->authChecker = $authChecker;
    }

    /**
     * @param TrainingCreateRequest $request
     * @return Training
     * @throws \Exception
     */
    public function createTraining(TrainingCreateRequest $request): Training
    {
        $this->hasAccessToTraining($request->getParticipant()->getUser());

        $participant = $request->getParticipant();

        $day = $this->dayService->getEntityBy(
            [
                'dayNumber' => $request->getDay(),
                'participant' => $participant
            ]
        );

        $training = $this->createEntity();
        $training->setDay($day);
        $training->setDate(new \DateTime());
        $training->setAmount($request->getAmount());
        $this->saveEntity($training);

        /** @var Day[] $days */
        $days = $this->dayService->getEntitiesBy(
            [
                'participant' => $participant
            ]
        );

        $marathonAmount = 0;

        foreach ($days as $day) {
            $trainings = $day->getTrainings();
            foreach ($trainings as $oldTraining) {
                $marathonAmount += $oldTraining->getAmount();
            }
        }

        if ($marathonAmount >= $participant->getChallenge()->getChallengeGoal() && $participant->getFinishDate() == null) {
            $participant->setFinishDate(new \DateTime());
            $this->participantService->saveEntity($participant);
        }

        return $training;
    }

    /**
     * @param TrainingUpdateRequest $request
     * @return Training
     */
    public function updatePatch(TrainingUpdateRequest $request): Training
    {
        $training = $request->getTraining();

        $this->hasAccessToTraining($training->getDay()->getParticipant()->getUser());

        if ($request->getTraining()) {
            $training->setAmount($request->getAmount());
            $this->saveEntity($training);
        }

        $this->dayService->prepareData($training->getDay());

        return $training;
    }

    /**
     * @param Training $training
     * @return Training
     */
    public function deleteTraining(Training $training): Training
    {
        $this->hasAccessToTraining($training->getDay()->getParticipant()->getUser());
        return $this->deleteEntity($training);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasAccessToTraining(User $user): bool
    {
        if (false === $this->authChecker->isGranted('ROLE_ADMIN') && $user != $this->currentUser) {
            throw new BadCredentialsException();
        }
        return true;
    }
}