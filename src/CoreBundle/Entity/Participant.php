<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use RestBundle\Entity\EntityInterface;
use RestBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Participant.
 *
 * @ORM\Entity()
 */
class Participant implements  EntityInterface
{
    use EntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose()
     * @JMS\SerializedName("id")
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var Challenge
     *
     * @JMS\Type("CoreBundle\Entity\Challenge")
     * @JMS\Groups({"post_participant", "get_participant"})
     *
     * @ORM\ManyToOne(targetEntity="Challenge", cascade={"persist"})
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="id", nullable=false)
     */
    private $challenge;

    /**
     * @var User
     *
     * @JMS\Type("CoreBundle\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var ArrayCollection|Day[]
     *
     * @JMS\Type("array<CoreBundle\Entity\Day>")
     * @JMS\Groups({"put_challenge", "get_challenge", "patch_challenge", "post_participant", "get_participant"})
     *
     * @ORM\OrderBy({"id" = "ASC"})
     * @ORM\OneToMany(targetEntity="Day", mappedBy="participant", cascade={"persist", "remove"})
     */
    private $days;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime<'U'>")
     *
     * @ORM\Column(name="finish_date", type="datetime", nullable=true)
     */
    private $finishDate;

    /**
     * Participant constructor.
     */
    public function __construct()
    {
        $this->days = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Challenge
     */
    public function getChallenge(): Challenge
    {
        return $this->challenge;
    }

    /**
     * @param Challenge $challenge
     * @return Participant
     */
    public function setChallenge(Challenge $challenge): self
    {
        $this->challenge = $challenge;

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
     * @return Participant
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Day[]|ArrayCollection
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param $days
     * @return Participant
     */
    public function setDays($days): self
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFinishDate()
    {
        return $this->finishDate;
    }

    /**
     * @param \DateTime $finishDate
     */
    public function setFinishDate(\DateTime $finishDate)
    {
        $this->finishDate = $finishDate;
    }
}
