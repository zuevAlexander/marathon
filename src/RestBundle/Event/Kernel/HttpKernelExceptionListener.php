<?php

namespace RestBundle\Event\Kernel;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class HttpKernelExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array('onKernelException', -127),
        );
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof EntityNotFoundException) {
            $response = new Response('Entity not found', Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
            $event->stopPropagation();
        }
    }
}
