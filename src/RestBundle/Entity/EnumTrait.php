<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

trait EnumTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $title;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $priority;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return EnumInterface|self
     */
    public function setTitle(string $title): EnumInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return EnumInterface|self
     */
    public function setPriority(int $priority): EnumInterface
    {
        $this->priority = $priority;

        return $this;
    }
}
