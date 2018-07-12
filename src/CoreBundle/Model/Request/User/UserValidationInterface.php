<?php

namespace CoreBundle\Model\Request\User;

interface UserValidationInterface
{
    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getPassword(): string;

}