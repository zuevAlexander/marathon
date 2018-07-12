<?php

namespace RestBundle\Request;

use RestBundle\Entity\EntityInterface;

/**
 * Class AbstractRequest.
 */
class AbstractRequest
{
    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'has')) {
            $property = lcfirst(substr($name, 3));
            if (property_exists($this, $property)) {
                if ($this->$property instanceof EntityInterface) {
                    return !$this->$property->isNull();
                } else {
                    return null !== $this->$property;
                }
            }

        }
        return false;
    }
}
