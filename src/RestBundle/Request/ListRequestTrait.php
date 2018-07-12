<?php

namespace RestBundle\Request;

use Doctrine\Common\Collections\Criteria;

/**
 * Class ListRequestTrait.
 */
trait ListRequestTrait
{
    protected $limit = 100;
    protected $page = 1;
    protected $sort = 'id';
    protected $order = Criteria::ASC;
    protected $includeDeleted;

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return self
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     *
     * @return self
     */
    public function setSort(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @param string $order
     *
     * @return self
     */
    public function setOrder(string $order): self
    {
        $this->order = $order;

        return $this;
    }
}
