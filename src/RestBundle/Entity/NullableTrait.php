<?php

namespace RestBundle\Entity;

/**
 * Class NullableTrait.
 */
trait NullableTrait
{
    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return !isset($this->id);
    }
}
