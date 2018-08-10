<?php

namespace CoreBundle\Model\Handler;


use CoreBundle\Model\Request\LDAPAuthorization\LDAPAuthorizationPostRequest;
use RestBundle\Handler\ProcessorInterface;

interface LDAPAuthorizationProcessorInterface extends ProcessorInterface
{
    public function processPostLogin(LDAPAuthorizationPostRequest $request): array;
}