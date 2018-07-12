<?php

namespace CoreBundle\Handler;

use CoreBundle\Entity\User;
use CoreBundle\Model\Request\User\UserListRequest;
use CoreBundle\Model\Request\User\UserLoginRequest;
use CoreBundle\Model\Request\User\UserRegisterRequest;
use CoreBundle\Model\Request\User\UserReadRequest;
use CoreBundle\Model\Handler\UserProcessorInterface;
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
    public function __construct(UserService $userService, TokenStorage $tokenStorage) {
        $this->userService = $userService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param UserLoginRequest $request
     *
     * @return User
     */
    public function processPostLogin(UserLoginRequest $request) : User
    {
        return $this->userService->generateApiKey($this->tokenStorage->getToken()->getUser());
    }

    /**
     * @param UserRegisterRequest $request
     *
     * @return User
     */
    public function processPostRegister(UserRegisterRequest $request) : User
    {
        return $this->userService->createUser($request, 'ROLE_ADMIN');
    }

    /**
     * @param UserRegisterRequest $request
     *
     * @return User
     */
    public function processPost(UserRegisterRequest $request) : User
    {
        return $this->userService->createUser($request);
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
}
