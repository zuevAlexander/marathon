<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use RestBundle\Entity\EntityInterface;
use RestBundle\Entity\EntityTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Challenge.
 *
 * @ORM\Entity()
 */
class Challenge implements  EntityInterface
{
    use EntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     * @JMS\SerializedName("id")
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", unique=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", unique=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", unique=true)
     */
    private $alias;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime<'U'>")
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime<'U'>")
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @var int
     *
     * @ORM\Column(name="daily_goal", type="integer", unique=false)
     */
    private $dailyGoal;

    /**
     * @var int
     *
     * @ORM\Column(name="challenge_goal", type="integer", unique=false)
     */
    private $challengeGoal;

    /**
     * @var Status
     *
     * @JMS\Type("CoreBundle\Entity\Status")
     *
     * @ORM\ManyToOne(targetEntity="Status", cascade={"persist"})
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=false)
     */
    private $status;

    /**
     * @var ArrayCollection|Participant[]
     *
     * @JMS\Type("array<CoreBundle\Entity\Participant>")
     * @JMS\Groups({"post_challenge", "get_challenge", "patch_challenge"})
     *
     * @ORM\OrderBy({"id" = "ASC"})
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="challenge", cascade={"persist", "remove"})
     */
    private $participants;

    /**
     * Day constructor.
     */
    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->status = new Status();
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Challenge
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return Challenge
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Challenge
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return Challenge
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return Challenge
     */
    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     * @return Challenge
     */
    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getDailyGoal(): int
    {
        return $this->dailyGoal;
    }

    /**
     * @param int $dailyGoal
     * @return Challenge
     */
    public function setDailyGoal(int $dailyGoal): self
    {
        $this->dailyGoal = $dailyGoal;

        return $this;
    }

    /**
     * @return int
     */
    public function getChallengeGoal(): int
    {
        return $this->challengeGoal;
    }

    /**
     * @param int $challengeGoal
     * @return Challenge
     */
    public function setChallengeGoal(int $challengeGoal): self
    {
        $this->challengeGoal = $challengeGoal;

        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return Challenge
     */
    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Participant[]|Collection
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Collection $participants
     * @return $this
     */
    public function setParticipants(Collection $participants)
    {
        $this->participants = $participants;

        return $this;
    }

    /**
     * @param Participant $participant
     * @return $this
     */
    public function addParticipant(Participant $participant)
    {
        $this->participants->add($participant);

        return $this;
    }
}
