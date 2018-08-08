<?php

namespace CoreBundle\Exception\Challenge;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class MarathonAlreadyFinishedException extends \RuntimeException
{
    public function __construct($message = 'Marathon has already finished. You can\'t change start or finish date of marathon.', $code = Response::HTTP_BAD_REQUEST, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
