<?php

namespace RestBundle\Repository;

use Doctrine\ORM\EntityNotFoundException;
use RestBundle\Entity\EntityInterface;
use RestBundle\Request\ListRequestInterface;

/**
 * Class EntityRepository.
 */
class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * {@inheritdoc}
     *
     * @throws EntityNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        /** @var EntityInterface $entity */
        $entity = parent::find($id, $lockMode, $lockVersion);

        $this->throwExceptionIfNoEntity($entity);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $entity = parent::findOneBy($criteria, $orderBy);

        $this->throwExceptionIfNoEntity($entity);

        return $entity;
    }

    /**
     * {@inheritdoc}
     *
     * @throws EntityNotFoundException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $result = parent::findBy($criteria, $orderBy, $limit, $offset);

        if (empty($result)) {
            throw new EntityNotFoundException();
        }

        return $result;
    }

    /**
     * @param $entity
     *
     * @throws EntityNotFoundException
     */
    private function throwExceptionIfNoEntity($entity)
    {
        if (!$entity instanceof EntityInterface) {
            throw new EntityNotFoundException();
        }

        $className = $this->getClassName();
        if (!$entity instanceof $className) {
            throw new EntityNotFoundException();
        }
    }

    /**
     * @param string $alias
     * @param ListRequestInterface $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilderWithLimitAndOrder(string $alias, ListRequestInterface $request)
    {
        return $this->createQueryBuilder($alias)
            ->setFirstResult(($request->getPage() - 1) * $request->getLimit())
            ->setMaxResults($request->getLimit())
            ->addOrderBy($alias . '.' . $request->getSort(), $request->getOrder());
    }

    /**
     * @param array $criteria
     *
     * @return int
     */
    public function countByCriteria(array $criteria): int
    {
        $persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);

        return $persister->count($criteria);
    }
}
