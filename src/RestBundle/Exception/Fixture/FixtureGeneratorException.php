<?php

namespace RestBundle\Exception\Fixture;

use Exception;

class FixtureGeneratorException extends \Exception
{
    public function __construct($message = 'Can not generate fixture', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
