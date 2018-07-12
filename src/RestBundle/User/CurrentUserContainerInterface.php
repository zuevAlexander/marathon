<?php

namespace RestBundle\User;

/**
 * Interface CurrentUserContainerInterface.
 */
interface CurrentUserContainerInterface
{
    /**
     * @return UserInterface
     */
    public function getCurrentUser() : UserInterface;

    /**
     * @param UserInterface $currentUser
     *
     * @return CurrentUserContainerInterface
     */
    public function setCurrentUser(UserInterface $currentUser) : self;

    /**
     * @param string $token
     */
    public function initCurrentUserByToken(string $token);

    /**
     * @param string $token
     *
     * @return UserInterface
     */
    public function getUserByToken(string $token): UserInterface;
}
