<?php

namespace CoreBundle\Exception\User;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class LdapException extends \RuntimeException
{
    public function __construct($message = 'LDAP payload does not contain information about user name', $code = Response::HTTP_FORBIDDEN, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
