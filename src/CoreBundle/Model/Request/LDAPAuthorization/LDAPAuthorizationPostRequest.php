<?php

namespace CoreBundle\Model\Request\LDAPAuthorization;

use RestBundle\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

class LDAPAuthorizationPostRequest extends AbstractRequest
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
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
}
