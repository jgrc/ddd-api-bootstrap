<?php
namespace Jgrc\Bootstrap\Ddd\Event;

interface DomainEventSubscriber
{
    public function notify(DomainEvent $event): void;
    public function isSubscribed(DomainEvent $event): bool;
}