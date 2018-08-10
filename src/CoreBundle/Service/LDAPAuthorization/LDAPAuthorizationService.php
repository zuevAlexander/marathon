<?php

namespace CoreBundle\Service\LDAPAuthorization;

use CoreBundle\Entity\User;
use CoreBundle\Service\User\UserService;
use CoreBundle\Exception\User\LdapException;
use CoreBundle\Exception\User\BadCredentialsException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Ldap\Ldap;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;

class LDAPAuthorizationService
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Ldap
     */
    private $ldapClient;

    /**
     * @var JWTManager
     */
    private $jwtManager;

    /**
     * @var string
     */
    private $ldapBaseDN;

    /**
     * LDAPAuthorizationService constructor.
     * @param UserService $userService
     * @param Ldap $ldapClient
     * @param JWTManager $jwtManager
     * @param string $ldapBaseDN
     */
    public function __construct(UserService $userService, Ldap $ldapClient, JWTManager $jwtManager, string $ldapBaseDN)
    {
        $this->userService = $userService;
        $this->ldapClient = $ldapClient;
        $this->jwtManager = $jwtManager;
        $this->ldapBaseDN = $ldapBaseDN;
    }

    public function login(string $username, string $password)
    {
        $username = preg_replace('/@.+/si', '', $username);
        $user = $this->ldapAuth($username, $password);
        $token = $this->jwtManager->create($user);

        return ['token' => $token, 'full_name' => $user->getFullName(), 'role' => $user->getHighestRole(), 'id' => $user->getId()];
    }

    /**
     * @param string $uid
     * @param string $bind_pwd
     * @return User
     * @throws BadCredentialsException
     * @throws LdapException
     */
    private function ldapAuth(string $uid, string $bind_pwd) : User
    {
        $this->ldapClient->bind();
        $query = $this->ldapClient->query($this->ldapBaseDN, "(&(objectclass=person)(uid=$uid))");
        $result = $query->execute()->toArray();

        if (!is_array($result) || !isset($result[0])) {
            throw new BadCredentialsException();
        }

        try {
            $this->ldapClient->bind($result[0]->getDn(), $bind_pwd);

            $fullName = $result[0]->getAttribute('cn')[0];
            $ldapLogin = $result[0]->getAttribute('uid')[0];
            $mail = $result[0]->getAttribute('mail')[0];

            if (isset($ldapLogin)) {
                try {
                    $user = $this->userService->getEntityBy(['name' => $ldapLogin]);
                } catch (EntityNotFoundException $exception) {
                    $user = $this->userService->createEntity();
                    $user->setUsername($ldapLogin)->setEmail($mail)->setFullName($fullName)->setRoles(['ROLE_USER']);
                    $this->userService->saveEntity($user);
                }
                return $user;
            } else {
                throw new LdapException();
            }
        } catch (\Exception $exception) {
            throw new BadCredentialsException();
        }
    }
}
