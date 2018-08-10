<?php
namespace CoreBundle\Service\Status;

use CoreBundle\Entity\Status;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DayService
 *
 * @method Status getEntityBy(array $criteria)
 */
class StatusService extends AbstractService
{
    const PENDING_STATUS = 'Pending';
    const OPEN_STATUS = 'Open';
    const CLOSED_STATUS = 'Closed';

    /**
     * UserHandler constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     */
    public function __construct(ContainerInterface $container, string $entityClass)
    {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
    }

}