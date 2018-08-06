<?php

namespace CoreBundle\Exception\Challenge;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class MarathonAlreadyStartedException extends \RuntimeException
{
    public function __construct($message = 'Marathon has already started. You can\'t change start date of marathon.', $code = Response::HTTP_FORBIDDEN, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
