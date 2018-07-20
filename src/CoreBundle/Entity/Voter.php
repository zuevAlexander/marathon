<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use RestBundle\Entity\EntityInterface;
use RestBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Voter.
 *
 * @ORM\Entity()
 */
class Voter implements  EntityInterface
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
     * @var string
     *
     * @Assert\Length(
     *     max="255"
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="voting_name", type="string")
     */
    private $votingName;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="4",
     *     max="255"
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="voting_email", type="string", unique=true)
     */
    private $votingEmail;

    /**
     * @var ArrayCollection|Vote[]
     *
     * @JMS\Type("array<CoreBundle\Entity\Vote>")
     *
     * @ORM\OrderBy({"id" = "ASC"})
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="voter", cascade={"persist", "remove"})
     */
    private $votes;

    /**
     * Voter constructor.
     */
    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getVotingName(): string
    {
        return $this->votingName;
    }

    /**
     * @param string $votingName
     * @return Voter
     */
    public function setVotingName(string $votingName): self
    {
        $this->votingName = $votingName;

        return $this;
    }

    /**
     * @return string
     */
    public function getVotingEmail(): string
    {
        return $this->votingEmail;
    }

    /**
     * @param string $votingEmail
     * @return Voter
     */
    public function setVotingEmail(string $votingEmail): self
    {
        $this->votingEmail = $votingEmail;

        return $this;
    }

    /**
     * @return Vote[]|ArrayCollection
     */
    public function getVotes(): ArrayCollection
    {
        return $this->votes;
    }

    /**
     * @param ArrayCollection $votes
     * @return Voter
     */
    public function setVotes(ArrayCollection $votes): self
    {
        $this->votes = $votes;

        return $this;
    }
}
