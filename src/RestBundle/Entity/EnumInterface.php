<?php

namespace RestBundle\Entity;

interface EnumInterface
{
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     *
     * @return EnumInterface
     */
    public function setTitle(string $title): self;
}
