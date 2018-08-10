<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\User;
use CoreBundle\Model\Request\User\UserListRequest;
use CoreBundle\Model\Request\User\UserReadRequest;
use CoreBundle\Model\Handler\UserProcessorInterface;
use CoreBundle\Model\Request\User\UserUpdateRequest;
use CoreBundle\Model\Request\User\UserDeleteRequest;
use CoreBundle\Service\User\UserService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class UserHandler
 */
class UserHandler implements UserProcessorInterface
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * UserHandler constructor.
     * @param UserService $userService
     * @param TokenStorage $tokenStorage
     */
    public function __construct(UserService $userService, TokenStorage $tokenStorage)
    {
        $this->userService = $userService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param UserReadRequest $request
     * @return User
     */
    public function processGet(UserReadRequest $request): User
    {
        return $this->userService->getUser($request);
    }

    /**
     * @param UserListRequest $request
     * @return array
     */
    public function processGetC(UserListRequest $request): array
    {
        return $this->userService->getCUsers($request);
    }

    /**
     * @param UserUpdateRequest $request
     * @return User
     */
    public function processPatch(UserUpdateRequest $request): User
    {
        return $this->userService->updatePatch($request);
    }

    /**
     * @param UserDeleteRequest $request
     * @return User
     */
    public function processDelete(UserDeleteRequest $request): User
    {
        return $this->userService->deleteEntity($request->getUser());
    }
}
