<?php


namespace GuardBundle\User;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    public function getApiKey();
}
