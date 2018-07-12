<?php

namespace RestBundle\Exception\Entity;

use Doctrine\ORM\EntityNotFoundException;

class DeletedEntityFoundException extends EntityNotFoundException
{
}
