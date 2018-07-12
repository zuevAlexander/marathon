<?php

namespace RestBundle\Exception;

interface ParametrizedExceptionInterface
{
    /**
     * @return array
     */
    public function getParameters() : array;
}
