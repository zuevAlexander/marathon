<?php

namespace CoreBundle\Model\Handler;

use CoreBundle\Entity\User;
use CoreBundle\Model\Request\User\UserLoginRequest;
use CoreBundle\Model\Request\User\UserReadRequest;
use CoreBundle\Model\Request\User\UserRegisterRequest;
use RestBundle\Handler\ProcessorInterface;

/**
 * Interface UserProcessorInterface
 */
interface UserProcessorInterface extends ProcessorInterface
{
    /**
     * @param UserRegisterRequest $request
     * @return User
     */
    public function processPostRegister(UserRegisterRequest $request): User;

    /**
     * @param UserLoginRequest $request
     * @return User
     */
    public function processPostLogin(UserLoginRequest $request): User;

    /**
     * @param UserReadRequest $request
     *
     * @return User
     */
    public function processGet(UserReadRequest $request): User;
}
