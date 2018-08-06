<?php

namespace CoreBundle\Form\User;

use CoreBundle\Model\Request\User\UserListRequest;
use RestBundle\Form\AbstractFormGetListType;

/**
 * Class UserListType
 * @package CoreBundle\Form\User
 */
class UserListType extends AbstractFormGetListType
{
    const DATA_CLASS = UserListRequest::class;
}
