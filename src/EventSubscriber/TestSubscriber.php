<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TestSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
    {}

    public function onKernelRequest(RequestEvent $event)
    {
        //dd($event);
        dump($event->getRequest());
        $this->logger->info("Je viens de créer mon premier subscriber");
        if($event->getRequest()->getLocale()==='fr') {
            $event->stopPropagation();
        }
    }

    public function onKernelController(ControllerEvent $event)
    {
        dd($event->getRequest());
        $this->logger->info("Je viens de créer mon premier subscriber");
    }

    public static function getSubscribedEvents()
    {
        return [
           RequestEvent::class => ['onKernelRequest',-1024],
           ControllerEvent::class => ['onKernelController',-1024]
        ];
    }
}
