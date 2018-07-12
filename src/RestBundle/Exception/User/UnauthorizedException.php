<?php

namespace RestBundle\Exception\User;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends \RuntimeException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = 'You are unauthorized', $code = Response::HTTP_UNAUTHORIZED, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
