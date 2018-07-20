<?php

namespace CoreBundle\Entity;

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
     * @var User
     *
     * @JMS\Type("CoreBundle\Entity\User")
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var Voter
     *
     * @JMS\Type("CoreBundle\Entity\Voter")
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="Voter", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="voter_id", referencedColumnName="id", nullable=false)
     */
    private $voter;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="place", type="integer", unique=false)
     */
    private $place;

    /**
     * Voter constructor.
     */
    public function __construct()
    {
        $this->user = new User();
        $this->voter = new Voter();
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
    public function getPlace(): int
    {
        return $this->place;
    }

    /**
     * @param int $place
     * @return Vote
     */
    public function setPlace(int $place): self
    {
        $this->place = $place;

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
     * @return Voter
     */
    public function getVoter(): Voter
    {
        return $this->voter;
    }

    /**
     * @param Voter $voter
     * @return Vote
     */
    public function setVoter(Voter $voter): self
    {
        $this->voter = $voter;

        return $this;
    }
}
