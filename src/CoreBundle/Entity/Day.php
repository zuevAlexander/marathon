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
     * @var User
     *
     * @JMS\Type("CoreBundle\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="day_number", type="integer", unique=false)
     */
    private $dayNumber;

    /**
     * @var ArrayCollection|Training[]
     *
     * @JMS\Groups({"get_user"})
     *
     * @ORM\OrderBy({"id" = "DESC"})
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
     * Day constructor.
     */
    public function __construct()
    {
        $this->trainings = new ArrayCollection();
        $this->prepareData();
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Day
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

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
     * @return int
     */
    public function getTimes(): int
    {
        return $this->times;
    }

    /**
     * @return Day
     */
    public function prepareData(): self
    {
        $trainings = $this->trainings;
        $this->times = $trainings->count();
        $this->pushUps = 0;

        foreach ($trainings as $training) {
            $this->pushUps += $training->getAmount();
        }

        return $this;
    }
}
