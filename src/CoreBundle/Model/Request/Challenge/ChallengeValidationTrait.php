<?php

namespace CoreBundle\Model\Request\Challenge;

use CoreBundle\Entity\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChallengeValidationTrait
 * @package CoreBundle\Model\Request\Challenge
 */
trait ChallengeValidationTrait
{
    /**
     * @var string
     *
     * @Assert\Length(
     *     min="2",
     *     max="255"
     * )
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="2",
     *     max="255"
     * )
     */
    private $author;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="2",
     *     max="255"
     * )
     */
    private $description;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="2",
     *     max="50"
     * )
     */
    private $alias;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    private $startDate;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    private $endDate;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    private $dailyGoal;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     */
    private $challengeGoal;

    /**
     * @var Status
     *
     * @Assert\NotBlank()
     */
    private $status;

    /**
     * @var ArrayCollection
     */
    protected $participants;
}