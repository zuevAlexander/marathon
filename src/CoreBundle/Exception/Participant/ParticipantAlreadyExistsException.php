<?php

namespace CoreBundle\Exception\Participant;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ParticipantAlreadyExistsException extends \RuntimeException
{
    public function __construct($message = 'Participant already exists', $code = Response::HTTP_BAD_REQUEST, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
