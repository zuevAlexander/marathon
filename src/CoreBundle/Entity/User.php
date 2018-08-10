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

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

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
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", unique=true)
     */
    private $name;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="email", type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", unique=false, nullable=true)
     */
    private $fullName;

    /**
     * @var array
     *
     * @JMS\Exclude()
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var ArrayCollection|Participant[]
     *
     * @JMS\Exclude
     *
     * @JMS\Type("array<CoreBundle\Entity\Participant>")
     *
     * @ORM\OrderBy({"id" = "ASC"})
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="user", cascade={"persist"})
     */
    private $participants;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    /**
     * User constructor.
     */
    public function __toString()
    {
        return $this->getEmail();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the username used to authenticate the user requires JWTManager
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the username used to authenticate the user requires UserInterface
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return User
     */
    public function setUsername(string $name): self
    {
        $this->name = $name;

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
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return User
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return '';
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
     * @return string
     */
    public function getHighestRole(): string
    {
        return in_array('ROLE_ADMIN', $this->roles) ? self::ROLE_ADMIN : self::ROLE_USER;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
