<?php
namespace Jgrc\Bootstrap\Kernel\Utils;

use Jgrc\Bootstrap\Ddd\Event\DomainEventPublisher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\DependencyInjection\Container;

class ApiDomainEventSubscriber implements EventSubscriberInterface
{
    private $container;
    private $eventSubscribers;

    public function __construct(Container $container, array $eventSubscribers)
    {
        $this->container = $container;
        $this->eventSubscribers = $eventSubscribers;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
            KernelEvents::RESPONSE => 'onResponse'
        ];
    }

    public function onException(GetResponseForExceptionEvent $event): void
    {
        DomainEventPublisher::instance()->clearEvents();
    }

    public function onResponse(FilterResponseEvent $event): void
    {
        if (false === DomainEventPublisher::instance()->hasEvents()) {
            return;
        }

        array_walk(
            $this->eventSubscribers,
            function ($service) {
                DomainEventPublisher::instance()->subscribe(
                    $this->container->get($service)
                );
            }
        );

        DomainEventPublisher::instance()->publish();
    }
}