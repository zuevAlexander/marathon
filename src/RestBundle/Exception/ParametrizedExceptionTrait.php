<?php

namespace RestBundle\Exception;

trait ParametrizedExceptionTrait
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     *
     * @return ParametrizedExceptionInterface
     */
    public function setParameters(array $parameters): ParametrizedExceptionInterface
    {
        $this->parameters = $parameters;

        return $this;
    }
}
