<?php
namespace CoreBundle\Service\CurrentUser;


use CoreBundle\Entity\User;
use RestBundle\Exception\Request\BadRequestException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;

/**
 * Class CurrentUserService
 * @package CoreBundle\Service\CurrentUser
 */
class CurrentUserService
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * CurrentUserService constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return User
     */
    public function getCurrentUser(): User
    {
        $token = $this->tokenStorage->getToken();

        if ($token instanceof TokenInterface) {
            return $token->getUser();
        }

        throw new BadRequestException(
            'Current user not authenticated.',
            404,
            new AuthenticationExpiredException(),
            ['current_user' => 'not authenticated.']
        );
    }
}