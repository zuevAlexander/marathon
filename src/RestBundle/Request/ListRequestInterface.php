<?php

namespace RestBundle\Request;

/**
 * Interface ListRequestInterface.
 */
interface ListRequestInterface
{
    /**
     * @return int
     */
    public function getLimit() : int;

    /**
     * @return string
     */
    public function getSort() : string;

    /**
     * @return string
     */
    public function getOrder() : string;

    /**
     * @return int
     */
    public function getPage() : int;
}
