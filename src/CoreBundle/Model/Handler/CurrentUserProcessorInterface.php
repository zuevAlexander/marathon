<?php

namespace CoreBundle\Model\Handler;

use CoreBundle\Entity\User;
use RestBundle\Handler\ProcessorInterface;
use CoreBundle\Model\Request\CurrentUser\CurrentUserRequest;


/**
 * Interface CurrentUserProcessorInterface
 */
interface CurrentUserProcessorInterface extends ProcessorInterface
{

    /**
     * @param CurrentUserRequest $request
     * @return User
     */
    public function processGet(CurrentUserRequest $request): User;

}
