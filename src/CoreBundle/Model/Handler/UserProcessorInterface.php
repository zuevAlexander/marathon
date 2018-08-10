<?php

namespace CoreBundle\Model\Handler;

use CoreBundle\Entity\User;
use CoreBundle\Model\Request\User\UserReadRequest;
use RestBundle\Handler\ProcessorInterface;

/**
 * Interface UserProcessorInterface
 */
interface UserProcessorInterface extends ProcessorInterface
{
    /**
     * @param UserReadRequest $request
     *
     * @return User
     */
    public function processGet(UserReadRequest $request): User;
}
