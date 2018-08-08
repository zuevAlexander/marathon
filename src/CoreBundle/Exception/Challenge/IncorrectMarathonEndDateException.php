<?php

namespace CoreBundle\Exception\Challenge;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class IncorrectMarathonEndDateException extends \RuntimeException
{
    public function __construct($message = 'End date of marathon should be more than current date', $code = Response::HTTP_BAD_REQUEST, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
