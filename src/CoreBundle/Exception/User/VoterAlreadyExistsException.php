<?php

namespace CoreBundle\Exception\User;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class VoterAlreadyExistsException extends \RuntimeException
{
    public function __construct($message = 'User with the same e-mail already exists', $code = Response::HTTP_FORBIDDEN, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
