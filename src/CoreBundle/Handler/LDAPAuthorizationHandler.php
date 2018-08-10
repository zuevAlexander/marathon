<?php

namespace CoreBundle\Handler;

use CoreBundle\Model\Handler\LDAPAuthorizationProcessorInterface;
use CoreBundle\Model\Request\LDAPAuthorization\LDAPAuthorizationPostRequest;
use CoreBundle\Service\LDAPAuthorization\LDAPAuthorizationService;

/**
 * Class GrouppedMenuItemHandler
 */
class LDAPAuthorizationHandler implements LDAPAuthorizationProcessorInterface
{
    /**
     * @var LDAPAuthorizationService
     */
    private $ldapAuthorizationService;

    /**
     * LDAPAuthorizationHandler constructor.
     * @param LDAPAuthorizationService $ldapAuthorizationService
     */
    public function __construct(LDAPAuthorizationService $ldapAuthorizationService)
    {
        $this->ldapAuthorizationService = $ldapAuthorizationService;
    }

    public function processPostLogin(LDAPAuthorizationPostRequest $request): array
    {
        return $this->ldapAuthorizationService->login($request->getName(), $request->getPassword());
    }
}
