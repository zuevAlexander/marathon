<?php

namespace RestBundle\Event;

use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProcessorEvent.
 */
class ProcessorEvent extends Event
{
    /**
     * @var ProcessorInterface
     */
    private $processor;
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var Request
     */
    private $request;

    /**
     * ProcessorEvent constructor.
     *
     * @param Request            $request
     * @param ProcessorInterface $processor
     * @param string             $methodName
     */
    public function __construct(Request $request, ProcessorInterface $processor, string $methodName)
    {
        $this->processor = $processor;
        $this->methodName = $methodName;
        $this->request = $request;
    }

    /**
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    /**
     * @param ProcessorInterface $processor
     *
     * @return ProcessorEvent
     */
    public function setProcessor(ProcessorInterface $processor): self
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @param string $methodName
     *
     * @return ProcessorEvent
     */
    public function setMethodName(string $methodName): self
    {
        $this->methodName = $methodName;

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
