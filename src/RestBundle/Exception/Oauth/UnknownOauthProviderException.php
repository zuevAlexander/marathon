<?php

namespace RestBundle\Exception\Oauth;

use Exception;

class UnknownOauthProviderException extends \Exception
{
    public function __construct($message = 'Unknown oauth provider', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
