<?php

namespace CoreBundle\Service\Challenge;

use CoreBundle\Entity\Challenge;
use CoreBundle\Entity\Participant;
use CoreBundle\Exception\Challenge\IncorrectMarathonEndDateException;
use CoreBundle\Exception\Challenge\MarathonAlreadyFinishedException;
use CoreBundle\Exception\Challenge\MarathonAlreadyStartedException;
use CoreBundle\Model\Request\Challenge\ChallengeCreateRequest;
use CoreBundle\Model\Request\Challenge\ChallengeListRequest;
use CoreBundle\Model\Request\Challenge\ChallengeReadRequest;
use CoreBundle\Model\Request\Challenge\ChallengeUpdateRequest;
use CoreBundle\Service\Participant\ParticipantService;
use CoreBundle\Service\Status\StatusService;
use CoreBundle\Service\Day\DayService;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Entity\EntityInterface;

/** @noinspection PhpHierarchyChecksInspection */
/**
 * Class ChallengeService
 *
 * @method Challenge createEntity()
 * @method Challenge getEntity(int $id)
 * @method Challenge getEntityBy(array $criteria)
 * @method Challenge deleteEntity(EntityInterface $entity, bool $flush = true)
 */
class ChallengeService extends AbstractService
{
    /**
     * @var DayService
     */
    private $dayService;

    /**
     * @var StatusService
     */
    private $statusService;

    /**
     * @var ParticipantService
     */
    private $participantService;

    /**
     * ChallengeService constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     * @param StatusService $statusService
     * @param ParticipantService $participantService
     */
    public function __construct(ContainerInterface $container, string $entityClass, StatusService $statusService, ParticipantService $participantService, DayService $dayService)
    {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
        $this->statusService = $statusService;
        $this->participantService = $participantService;
        $this->dayService = $dayService;
    }

    /**
     * @param ChallengeCreateRequest $request
     * @return Challenge
     */
    public function createChallenge(ChallengeCreateRequest $request): Challenge
    {
        $challenge = $this->createEntity();
        $startDate = new \DateTime();
        $endDate = new \DateTime();
        $status = $request->getStatus()->isNull() ? $this->statusService->getEntityBy(['name' => StatusService::PENDING_STATUS]): $request->getStatus();

        $challenge->setName($request->getName());
        $challenge->setAuthor($request->getAuthor());
        $challenge->setStatus($status);
        $challenge->setDescription($request->getDescription());
        $challenge->setStartDate($startDate->setTimestamp($request->getStartDate()));
        $challenge->setEndDate($endDate->setTimestamp($request->getEndDate()));
        $challenge->setDailyGoal($request->getDailyGoal());
        $challenge->setChallengeGoal($request->getChallengeGoal());
        $challenge->setAlias(str_replace(' ', '_', strtolower($request->getAlias())));

        $this->saveEntity($challenge);

        /**
         * User[] $users
         */
        $newParticipants = $request->getParticipants();
        foreach ($newParticipants as $newParticipant) {
            $participant = new Participant();
            $participant->setUser($newParticipant->getUser());
            $participant->setChallenge($challenge);

            $challengeDuration = ($request->getEndDate() - $request->getStartDate()) / 60 / 60 / 24 + 1;
            $participant = $this->participantService->fillDays($participant, $challengeDuration);

            $challenge->addParticipant($participant);
        }

        $this->saveEntity($challenge);

        return $challenge;
    }

    /**
     * @param ChallengeReadRequest $request
     * @return Challenge
     */
    public function getChallenge(ChallengeReadRequest $request): Challenge
    {
        $challenge = $request->getChallenge();

        foreach ($challenge->getParticipants() as $participant) {
            foreach ($participant->getDays() as $day) {
                $this->dayService->prepareData($day);
            }
        }

        return $challenge;
    }

    /**
     * @param ChallengeListRequest $request
     * @return array
     */
    public function getChallenges(ChallengeListRequest $request): array
    {
        return $this->getEntitiesByWithListRequest([], $request);
    }

    /**
     * @param ChallengeUpdateRequest $request
     * @return Challenge
     */
    public function updateChallenge(ChallengeUpdateRequest $request): Challenge
    {
        $challenge = $request->getChallenge();

        if ($request->getName()) {
            $challenge->setName($request->getName());
        }

        if ($request->getAuthor()) {
            $challenge->setAuthor($request->getAuthor());
        }

        if ($request->getDescription()) {
            $challenge->setDescription($request->getDescription());
        }

        if ($request->getAlias()) {
            $challenge->setAlias($request->getAlias());
        }

        if ($request->getStartDate() || $request->getEndDate()) {
            if ($request->getStartDate() && new \DateTime() > $challenge->getStartDate()) {
                throw new MarathonAlreadyStartedException();
            }

            if (new \DateTime() > $challenge->getEndDate()) {
                throw new MarathonAlreadyFinishedException();
            }

            if ($request->getEndDate() && time() > $request->getEndDate()) {
                throw new IncorrectMarathonEndDateException();
            }

            $startTimestamp = new \DateTime();
            $endTimestamp = new \DateTime();
            $startDate = $request->getStartDate() ? $startTimestamp->setTimestamp($request->getStartDate()) : $challenge->getStartDate();
            $endDate = $request->getEndDate() ? $endTimestamp->setTimestamp($request->getEndDate()) : $challenge->getEndDate();

            $challenge->setStartDate($startDate);
            $challenge->setEndDate($endDate);

            foreach ($challenge->getParticipants() as $participant) {
                $challengeDuration = ($challenge->getEndDate()->getTimestamp() - $challenge->getStartDate()->getTimestamp()) / 60 / 60 / 24 + 1;
                $this->participantService->changeDays($participant, $challengeDuration);
            }
        }

        if ($request->getDailyGoal()) {
            $challenge->setDailyGoal($request->getDailyGoal());
        }

        if ($request->getChallengeGoal()) {
            $challenge->setChallengeGoal($request->getChallengeGoal());
        }

        if (!$request->getStatus()->isNull()) {
            $challenge->setStatus($request->getStatus());
        }

        $this->saveEntity($challenge);

        return $challenge;
    }
}
