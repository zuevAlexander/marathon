<?php

namespace RestBundle\Exception\Request;

use Exception;
use Symfony\Component\Form\FormErrorIterator;

class BadRequestException extends \RuntimeException
{
    /**
     * @var FormErrorIterator
     */
    private $errorInfo = [];

    /**
     * @param string    $message
     * @param int       $code
     * @param Exception $previous
     * @param array     $errorInfo
     */
    public function __construct($message, $code, Exception $previous, $errorInfo = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errorInfo = $errorInfo;
    }

    /**
     * @return FormErrorIterator
     */
    public function getErrorInfo()
    {
        return $this->errorInfo;
    }
}
