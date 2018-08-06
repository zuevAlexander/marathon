<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use RestBundle\Entity\EntityInterface;
use RestBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Day.
 *
 * @ORM\Entity()
 */
class Day implements  EntityInterface
{
    use EntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Exclude()
     * @JMS\SerializedName("id")
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var Participant
     *
     * @JMS\Exclude()
     * @JMS\Type("CoreBundle\Entity\Participant")
     *
     * @ORM\ManyToOne(targetEntity="Participant", cascade={"persist"})
     * @ORM\JoinColumn(name="participant_id", referencedColumnName="id", nullable=false)
     */
    private $participant;

    /**
     * @var int
     *
     * @ORM\Column(name="day_number", type="integer", unique=false)
     */
    private $dayNumber;

    /**
     * @var ArrayCollection|Training[]
     *
//     * @JMS\Groups({"get_user"})
     * @JMS\Exclude()
     *
     * @ORM\OrderBy({"id" = "ASC"})
     * @ORM\OneToMany(targetEntity="Training", mappedBy="day", cascade={"persist", "remove"})
     */
    private $trainings;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private $pushUps;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private $times;

    /**
     * @var boolean
     *
     * @JMS\Type("boolean")
     */
    private $isCurrent;

    /**
     * Day constructor.
     */
    public function __construct()
    {
        $this->trainings = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getDayNumber(): int
    {
        return $this->dayNumber;
    }

    /**
     * @param int $dayNumber
     * @return Day
     */
    public function setDayNumber(int $dayNumber): self
    {
        $this->dayNumber = $dayNumber;

        return $this;
    }

    /**
     * @return Participant
     */
    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    /**
     * @param Participant $participant
     * @return Day
     */
    public function setParticipant(Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * @return Training[]|ArrayCollection
     */
    public function getTrainings()
    {
        return $this->trainings;
    }

    /**
     * @param $trainings
     * @return Day
     */
    public function setTrainings($trainings): self
    {
        $this->trainings = $trainings;

        return $this;
    }

    /**
     * @return int
     */
    public function getPushUps(): int
    {
        return $this->pushUps;
    }

    /**
     * @param int $pushUps
     * @return Day
     */
    public function setPushUps(int $pushUps): self
    {
        $this->pushUps = $pushUps;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimes(): int
    {
        return $this->times;
    }

    /**
     * @param int $times
     * @return Day
     */
    public function setTimes(int $times): self
    {
        $this->times = $times;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsCurrent(): bool
    {
        return $this->isCurrent;
    }

    /**
     * @param bool $isCurrent
     * @return Day
     */
    public function setIsCurrent(bool $isCurrent): self
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

//    /**
//     * @return Day
//     */
//    public function prepareData(): self
//    {
//        $trainings = $this->trainings;
//        $this->times = $trainings->count();
//        $this->pushUps = 0;
//
//        foreach ($trainings as $training) {
//            $this->pushUps += $training->getAmount();
//        }
//
//        $this->isCurrent = ceil((time() - self::FIRST_DAY) / 24 / 60 / 60) == $this->getDayNumber();
//
//        return $this;
//    }
}
