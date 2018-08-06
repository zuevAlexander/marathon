<?php

namespace CoreBundle\Form\CurrentUser;

use CoreBundle\Model\Request\CurrentUser\CurrentUserRequest;
use RestBundle\Form\AbstractFormType;


/**
 * Class CurrentUserType
 * @package CoreBundle\Form\CurrentUser
 */
class CurrentUserType extends AbstractFormType
{
    const DATA_CLASS = CurrentUserRequest::class;
}
