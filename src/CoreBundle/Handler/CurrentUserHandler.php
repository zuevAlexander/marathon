<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\User;
use CoreBundle\Service\CurrentUser\CurrentUserService;
use CoreBundle\Model\Handler\CurrentUserProcessorInterface;
use CoreBundle\Model\Request\CurrentUser\CurrentUserRequest;


/**
 * Class CurrentUserHandler
 */
class CurrentUserHandler implements CurrentUserProcessorInterface
{
    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    /**
     * CurrentUserHandler constructor.
     * @param CurrentUserService $currentUserService
     */
    public function __construct(CurrentUserService $currentUserService)
    {
        $this->currentUserService = $currentUserService;
    }

    /**
     * @param CurrentUserRequest $request
     * @return User
     */
    public function processGet(CurrentUserRequest $request): User
    {
        return $this->currentUserService->getCurrentUser();
    }
}
