<?php

namespace CoreBundle\Exception\Vote;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class VoteAlreadyExistsException extends \RuntimeException
{
    public function __construct($message = 'You have already voted for this challenge', $code = Response::HTTP_FORBIDDEN, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
