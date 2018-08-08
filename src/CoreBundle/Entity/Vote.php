<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use RestBundle\Entity\EntityInterface;
use RestBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Vote.
 *
 * @ORM\Entity()
 */
class Vote implements  EntityInterface
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
     * @var Challenge
     *
     * @JMS\Type("CoreBundle\Entity\Challenge")
     * @JMS\Exclude()
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
     * @var ArrayCollection|Rating[]
     *
     * @JMS\Type("array<CoreBundle\Entity\Rating>")
     *
     * @ORM\OrderBy({"id" = "ASC"})
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="vote", cascade={"persist", "remove"})
     */
    private $ratings;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime<'U'>")
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * Vote constructor.
     */
    public function __construct()
    {
        $this->user = new User();
        $this->challenge = new Challenge();
        $this->ratings = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Rating[]|ArrayCollection
     */
    public function getRatings(): ArrayCollection
    {
        return $this->ratings;
    }

    /**
     * @param ArrayCollection $ratings
     * @return Vote
     */
    public function setRatings(ArrayCollection $ratings): self
    {
        $this->ratings = $ratings;

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
     * @return Vote
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
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
     * @return Vote
     */
    public function setChallenge(Challenge $challenge): self
    {
        $this->challenge = $challenge;

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
     * @return Vote
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
