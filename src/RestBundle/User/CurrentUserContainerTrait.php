<?php

namespace RestBundle\User;

use Doctrine\ORM\EntityNotFoundException;
use RestBundle\Exception\User\UnauthorizedException;
use RestBundle\Exception\User\UserByTokenNotImplementedException;

/**
 * Class CurrentUserContainerTrait.
 */
trait CurrentUserContainerTrait
{
    /**
     * @var UserInterface
     */
    protected $currentUser;

    /**
     * @return UserInterface
     */
    public function getCurrentUser(): UserInterface
    {
        return $this->currentUser;
    }

    /**
     * @param UserInterface $currentUser
     *
     * @return self|$this
     */
    public function setCurrentUser(UserInterface $currentUser): CurrentUserContainerInterface
    {
        $this->currentUser = $currentUser;

        return $this;
    }

    /**
     * @param string $token
     */
    public function initCurrentUserByToken(string $token)
    {
        if (empty($token)) {
            throw new UnauthorizedException();
        }

        try {
            $this->setCurrentUser($this->getUserByToken($token));
        } catch (EntityNotFoundException $exception) {
            throw new UnauthorizedException();
        }
    }

    /**
     * @param string $token
     *
     * @return UserInterface
     *
     * @throws UserByTokenNotImplementedException
     */
    public function getUserByToken(string $token): UserInterface
    {
        throw new UserByTokenNotImplementedException();
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return isset($this->currentUser);
    }
}
