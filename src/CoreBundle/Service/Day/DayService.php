<?php
namespace CoreBundle\Service\Day;

use CoreBundle\Entity\Day;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DayService
 *
 * @method Day getEntityBy(array $criteria)
 */
class DayService extends AbstractService
{
    /**
     * UserHandler constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     */
    public function __construct(ContainerInterface $container, string $entityClass
    ) {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
    }

}