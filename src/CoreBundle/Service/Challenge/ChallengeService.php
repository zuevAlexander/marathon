<?php

namespace CoreBundle\Service\Challenge;

use CoreBundle\Entity\Challenge;
use CoreBundle\Entity\Participant;
use CoreBundle\Entity\User;
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
     * @param DayService $dayService
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

        $newParticipants = [];
        foreach ($request->getParticipants() as $participant) {
            $newParticipants[] = $participant->getUser();
        }
        $challenge = $this->addParticipants($newParticipants, $challenge);

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

        return $this->prepareDays($challenge);
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

        $challenge->setName($request->getName());
        $challenge->setAuthor($request->getAuthor());
        $challenge->setDescription($request->getDescription());
        $challenge->setAlias($request->getAlias());
        $challenge->setDailyGoal($request->getDailyGoal());
        $challenge->setChallengeGoal($request->getChallengeGoal());
        $challenge->setStatus($request->getStatus());

        $endDateChanged = $request->getEndDate() != $challenge->getEndDate()->getTimestamp();
        $startDateChanged = $request->getStartDate() != $challenge->getStartDate()->getTimestamp();

        if ($startDateChanged || $endDateChanged) {
            if ($startDateChanged && new \DateTime() > $challenge->getStartDate()) {
                throw new MarathonAlreadyStartedException();
            }

            if ($endDateChanged && new \DateTime() > $challenge->getEndDate()) {
                throw new MarathonAlreadyFinishedException();
            }

            if ($endDateChanged  && time() > $request->getEndDate()) {
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

        $newParticipants = [];
        foreach ($request->getParticipants() as $participant) {
            $newParticipants[] = $participant->getUser();
        }

        $oldParticipants = [];
        foreach ($request->getChallenge()->getParticipants() as $participant) {
            $oldParticipants[] = $participant->getUser();
        }

        $participantsToAdd = array_diff($newParticipants, $oldParticipants);
        $participantsToDelete = array_diff($oldParticipants, $newParticipants);

        $challenge = $this->addParticipants($participantsToAdd, $challenge);
        $challenge = $this->removeParticipants($participantsToDelete, $challenge);

        $this->saveEntity($challenge);

        return $this->prepareDays($challenge);
    }

    /**
     * @param array $newParticipants
     * @param Challenge $challenge
     * @return Challenge
     */
    public function addParticipants(array $newParticipants = [], Challenge $challenge): Challenge
    {
        foreach ($newParticipants as $user) {
            $participant = new Participant();
            $participant->setUser($user);
            $participant->setChallenge($challenge);

            $challengeDuration = ($challenge->getEndDate()->getTimestamp() - $challenge->getStartDate()->getTimestamp()) / 60 / 60 / 24 + 1;
            $participant = $this->participantService->fillDays($participant, $challengeDuration);

            $challenge->addParticipant($participant);
        }

        return $challenge;
    }

    /**
     * @param array $oldParticipants
     * @param Challenge $challenge
     * @return Challenge
     */
    public function removeParticipants(array $oldParticipants = [], Challenge $challenge): Challenge
    {
        foreach ($oldParticipants as $user) {
            $participant = $this->participantService->getEntityBy(['user' => $user, 'challenge' => $challenge]);
            $challenge->removeParticipant($participant);
        }

        $this->saveEntity($challenge);

        return $challenge;
    }

    /**
     * @param Challenge $challenge
     * @return Challenge
     */
    public function prepareDays(Challenge $challenge): Challenge
    {
        foreach ($challenge->getParticipants() as $participant) {
            foreach ($participant->getDays() as $day) {
                $this->dayService->prepareData($day);
            }
        }
        
        return $challenge;
    }

    /**
     * @param Challenge $challenge
     * @return Participant
     */
    public function joinChallenge(Challenge $challenge): Participant
    {
        return $this->participantService->createParticipant($challenge, $this->container->get('security.token_storage')->getToken()->getUser());
    }
}
