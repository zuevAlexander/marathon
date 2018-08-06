<?php

namespace CoreBundle\Model\Request\Challenge;

use RestBundle\Request\ListRequestInterface;
use RestBundle\Request\ListRequestTrait;
use RestBundle\Request\AbstractRequest;

/**
 * Class ChallengeListRequest
 * @package CoreBundle\Model\Request\Challenge
 */
class ChallengeListRequest extends AbstractRequest implements ListRequestInterface
{
    use ListRequestTrait;
}
