<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use RestBundle\Entity\EntityInterface;
use RestBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Training.
 *
 * @ORM\Entity()
 */
class Training implements  EntityInterface
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
     * @var Day
     *
     * @JMS\Type("CoreBundle\Entity\Day")
     *
     * @ORM\ManyToOne(targetEntity="Day", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="day_id", referencedColumnName="id", nullable=false)
     */
    private $day;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer", unique=false)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime<'U'>")
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date;

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
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return Training
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Day
     */
    public function getDay(): Day
    {
        return $this->day;
    }

    /**
     * @param Day $day
     * @return Training
     */
    public function setDay(Day $day): self
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Training
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
