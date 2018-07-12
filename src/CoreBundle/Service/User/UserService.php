<?php

namespace CoreBundle\Service\User;

use CoreBundle\Entity\Day;
use CoreBundle\Entity\User;
use CoreBundle\Model\Request\User\UserListRequest;
use CoreBundle\Model\Request\User\UserReadRequest;
use CoreBundle\Model\Request\User\UserRegisterRequest;
use CoreBundle\Service\Day\DayService;
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
     * @var DayService
     */
    private $dayService;

    /**
     * UserService constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     * @param UserPasswordEncoderInterface $encoder
     * @param DayService $dayService
     */
    public function __construct(ContainerInterface $container, string $entityClass, UserPasswordEncoderInterface $encoder, DayService $dayService
    ) {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
        $this->encoder = $encoder;
        $this->dayService = $dayService;
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

        $this->fillDays($user);

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

        /** @var User $user */
        foreach ($users['items'] as $user) {
            foreach ($user->getDays() as $day) {
                $day->prepareData();
            }
        }

        return $users;
    }

    /**
     * @param UserReadRequest $request
     * @return User
     */
    public function getUser(UserReadRequest $request): User
    {
        $user = $this->getEntity($request->getUser());
        foreach ($user->getDays() as $day) {
            $day->prepareData();
        }

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
     * @param User $user
     */
    public function fillDays(User $user)
    {
        for ($i = 1; $i <= 30; $i++) {
            $day = new Day();
            $day->setDayNumber($i);
            $day->setUser($user);
            $this->dayService->saveEntity($day);
        }
    }
}