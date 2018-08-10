<?php

namespace CoreBundle\Service\User;

use CoreBundle\Entity\User;
use CoreBundle\Model\Request\User\UserListRequest;
use CoreBundle\Model\Request\User\UserReadRequest;
use CoreBundle\Model\Request\User\UserUpdateRequest;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RestBundle\Entity\EntityInterface;
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
     * @param UserUpdateRequest $request
     * @return User
     */
    public function updatePatch(UserUpdateRequest $request): User
    {
        $user = $request->getUser();

        if ($request->getName()) {
            $user->setUsername($request->getName());
        }

        if ($request->getEmail()) {
            $user->setEmail($request->getEmail());
        }

        if ($request->getEmail()) {
            $user->setEmail($request->getEmail());
        }

        $this->saveEntity($user);

        return $user;
    }
}