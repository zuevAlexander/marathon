<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use RestBundle\Entity\EntityTrait;
use RestBundle\User\UserInterface as NDUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 *
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\UserRepository")
 */
class User implements  UserInterface, NDUserInterface
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
     * @Assert\Length(
     *     min="4",
     *     max="255"
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="4",
     *     max="255"
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="4",
     *     max="255"
     * )
     * @Assert\NotBlank()
     *
     * @JMS\Exclude()
     *
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\Groups({"post_user_login", "post_user_register"})
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $apiKey;

    /**
     * @var array
     *
     * @JMS\Exclude()
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var ArrayCollection|Day[]
     *
     * @JMS\Type("array<CoreBundle\Entity\Day>")
     * @JMS\Groups({"get_user", "get_users", "post_user"})
     *
     * @ORM\OrderBy({"id" = "ASC"})
     * @ORM\OneToMany(targetEntity="Day", mappedBy="user", cascade={"persist", "remove"})
     */
    private $days;

    /**
     * @var int
     *
     * @JMS\Type("int")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $finishDate;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->days = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

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
     * @return User
     */
    public function setDays($days): self
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return int
     */
    public function getFinishDate(): int
    {
        return $this->finishDate;
    }

    /**
     * @param int $finishDate
     */
    public function setFinishDate(int $finishDate)
    {
        $this->finishDate = $finishDate;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
