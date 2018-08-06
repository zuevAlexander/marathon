<?php
namespace CoreBundle\Service\Day;

use CoreBundle\Entity\Day;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DayService
 *
 * @method Day getEntityBy(array $criteria)
 */
class DayService extends AbstractService
{
    /**
     * UserHandler constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     */
    public function __construct(ContainerInterface $container, string $entityClass
    ) {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
    }

    /**
     * @param Day $day
     * @return Day
     */
    public function prepareData(Day $day): Day
    {
        $trainings = $day->getTrainings();
        $day->setTimes($trainings->count());

        $dayAmount = 0;
        foreach ($trainings as $training) {
            $dayAmount += $training->getAmount();
        }

        $day->setAmount($dayAmount);

        $day->setIsCurrent(ceil((time() - $day->getParticipant()->getChallenge()->getStartDate()->getTimestamp()) / 24 / 60 / 60) == $day->getDayNumber());

        return $day;
    }
}