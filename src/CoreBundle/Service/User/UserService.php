<?php

namespace CoreBundle\Service\User;

use CoreBundle\Entity\Day;
use CoreBundle\Entity\User;
use CoreBundle\Model\Request\User\UserListRequest;
use CoreBundle\Model\Request\User\UserReadRequest;
use CoreBundle\Model\Request\User\UserRegisterRequest;
use CoreBundle\Model\Request\User\UserUpdateRequest;
use CoreBundle\Service\Day\DayService;
use Doctrine\Common\Collections\ArrayCollection;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Entity\EntityInterface;
use CoreBundle\Exception\User\UserAlreadyExistsException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/** @noinspection PhpHierarchyChecksInspection */
/**
 * Class UserService
 *
 * @method User createEntity()
 * @method User getEntity(int $id)
 * @method User getEntityBy(array $criteria)
 * @method User deleteEntity(EntityInterface $entity, bool $flush = true)
 */
class UserService extends AbstractService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserService constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(ContainerInterface $container, string $entityClass, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
        $this->encoder = $encoder;
    }

    /**
     * @param UserRegisterRequest $request
     * @param string $role
     * @return User
     */
    public function createUser(UserRegisterRequest $request, string $role = 'ROLE_USER'): User
    {
        try {
            $this->getEntityBy(['username' => $request->getUsername()]);
            throw new UserAlreadyExistsException();
        } catch (EntityNotFoundException $e) {
            // we haven't found user - that's ok
        }

        $user = $this->createEntity();
        $user->setUsername($request->getUsername());
        $user->setEmail($request->getEmail());
        $user->setPassword($this->encoder->encodePassword($user, $request->getPassword()));
        $user->setRoles(array($role));
        $this->generateApiKey($user);

        return $user;
    }

    /**
     * @param UserListRequest $request
     * @return array
     */
    public function getCUsers(UserListRequest $request): array
    {
        $users = $this->getEntitiesByWithListRequestAndTotal(
            [],
            $request
        );

        return $users['items'];
    }

    /**
     * @param UserReadRequest $request
     * @return User
     */
    public function getUser(UserReadRequest $request): User
    {
        $user = $this->getEntity($request->getUser());

        return $user;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function generateApiKey(User $user) : User
    {
        $user->setApiKey(bin2hex(random_bytes(20)));
        $this->saveEntity($user);
        return $user;
    }

    /**
     * @param UserUpdateRequest $request
     * @return User
     */
    public function updatePatch(UserUpdateRequest $request): User
    {
        $user = $request->getUser();

        if ($request->getUsername()) {
            $user->setUsername($request->getUsername());
        }

        if ($request->getEmail()) {
            $user->setEmail($request->getEmail());
        }

        $this->saveEntity($user);

        return $user;
    }
}