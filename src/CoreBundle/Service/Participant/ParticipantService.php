<?php
namespace CoreBundle\Service\Participant;

use CoreBundle\Entity\Day;
use CoreBundle\Entity\User;
use CoreBundle\Entity\Challenge;
use CoreBundle\Entity\Participant;
use CoreBundle\Exception\Participant\ParticipantAlreadyExistsException;
use CoreBundle\Model\Request\Participant\ParticipantCreateRequest;
use CoreBundle\Model\Request\Participant\ParticipantReadRequest;
use CoreBundle\Service\Day\DayService;
use Doctrine\ORM\EntityNotFoundException;
use RestBundle\Entity\EntityInterface;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DayService
 *
 * @method Participant createEntity()
 * @method Participant getEntity(int $id)
 * @method Participant getEntityBy(array $criteria)
 * @method Participant deleteEntity(EntityInterface $entity, bool $flush = true)
 */
class ParticipantService extends AbstractService
{
    /**
     * @var DayService
     */
    private $dayService;

    /**
     * ParticipantService constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     * @param DayService $dayService
     */
    public function __construct(ContainerInterface $container, string $entityClass, DayService $dayService)
    {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
        $this->dayService = $dayService;
    }

    /**
     * @param Challenge $challenge
     * @param User $user
     * @return Participant
     */
    public function createParticipant(Challenge $challenge, User $user): Participant
    {
        try {
            $this->getEntityBy(['user' => $user, 'challenge' => $challenge]);
            throw new ParticipantAlreadyExistsException();
        } catch (EntityNotFoundException $e) {
            // we haven't found participant - that's ok
        }

        $participant = $this->createEntity();
        $participant->setUser($user);
        $participant->setChallenge($challenge);

        $challengeDuration = ($challenge->getEndDate()->getTimestamp() - $challenge->getStartDate()->getTimestamp()) / 60 / 60 / 24 + 1;
        $participant = $this->fillDays($participant, $challengeDuration);

        $this->saveEntity($participant);

        return $participant;
    }

    /**
     * @param ParticipantReadRequest $request
     * @return Participant
     */
    public function getParticipant(ParticipantReadRequest $request): Participant
    {
        $participant = $request->getParticipant();

        foreach ($participant->getDays() as $day) {
            $this->dayService->prepareData($day);
        }

        return $participant;
    }

    /**
     * @param Participant $participant
     * @param int $challengeDuration
     * @return Participant
     */
    public function fillDays(Participant $participant, int $challengeDuration): Participant
    {
        for ($i = 1; $i <= $challengeDuration; $i++) {
            $day = new Day();
            $day->setDayNumber($i);
            $day->setParticipant($participant);
            $this->dayService->saveEntity($day);
        }

        $days = $this->dayService->getEntitiesBy(["participant" => $participant]);

        /** @var Day $day */
        foreach ($days as $day) {
            $this->dayService->prepareData($day);
        }

        $participant->setDays($days);

        return $participant;
    }

    /**
     * @param Participant $participant
     * @param int $challengeDuration
     * @return Participant
     */
    public function changeDays(Participant $participant, int $challengeDuration): Participant
    {
        $daysAmount = $participant->getDays()->count();
        if ($challengeDuration < $daysAmount) {
            foreach ($participant->getDays() as $day) {
                if ($day->getDayNumber() > $challengeDuration) {
                    $this->dayService->deleteEntity($day);
                }
            }
        } else {
            for ($i = $daysAmount + 1; $i <= $challengeDuration; $i++) {
                $day = new Day();
                $day->setDayNumber($i);
                $day->setParticipant($participant);
                $this->dayService->saveEntity($day);
            }
        }

        foreach ($participant->getDays() as $day) {
            $this->dayService->prepareData($day);
        }

        return $participant;
    }
}
