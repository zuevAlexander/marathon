<?php

namespace CoreBundle\Model\Request\User;

use RestBundle\Request\ListRequestInterface;
use RestBundle\Request\ListRequestTrait;
use RestBundle\Request\AbstractRequest;

/**
 * Class UserListRequest
 */
class UserListRequest extends AbstractRequest implements ListRequestInterface
{
    use ListRequestTrait;
}
