<?php

namespace CoreBundle\Model\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserValidationTrait.
 */
trait UserValidationTrait
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
    private $username = '';

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="4",
     *     max="255"
     * )
     *
     * @Assert\NotBlank()
     */
    private $password = '1234';

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="4",
     *     max="255"
     * )
     *
     * @Assert\NotBlank()
     */
    private $email = '';
}