<?php

namespace CoreBundle\Form\Challenge;

use CoreBundle\Model\Request\Challenge\ChallengeListRequest;
use RestBundle\Form\AbstractFormGetListType;

/**
 * Class ChallengeListType
 * @package CoreBundle\Form\Challenge
 */
class ChallengeListType extends AbstractFormGetListType
{
    const DATA_CLASS = ChallengeListRequest::class;
}
