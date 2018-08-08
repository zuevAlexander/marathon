<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use RestBundle\Entity\EntityInterface;
use RestBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Rating.
 *
 * @ORM\Entity()
 */
class Rating implements  EntityInterface
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
     * @JMS\Type("CoreBundle\Entity\Participant")
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="Participant", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="participant_id", referencedColumnName="id", nullable=false)
     */
    private $participant;

    /**
     * @var Vote
     *
     * @JMS\Type("CoreBundle\Entity\Vote")
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="Vote", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="vote_id", referencedColumnName="id", nullable=false)
     */
    private $vote;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="place", type="integer", unique=false)
     */
    private $place;

    /**
     * Vote constructor.
     */
    public function __construct()
    {
        $this->participant = new Participant();
        $this->vote = new Vote();
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
     * @return Rating
     */
    public function setPlace(int $place): self
    {
        $this->place = $place;

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
     * @return Rating
     */
    public function setParticipant(Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * @return Vote
     */
    public function getVote(): Vote
    {
        return $this->vote;
    }

    /**
     * @param Vote $vote
     * @return Rating
     */
    public function setVote(Vote $vote): self
    {
        $this->vote = $vote;

        return $this;
    }
}
