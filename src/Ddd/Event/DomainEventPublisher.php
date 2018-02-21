<?php
namespace Jgrc\Bootstrap\Ddd\Event;

class DomainEventPublisher
{
    private static $instance;

    private $subscribers;
    private $events;

    private function __construct()
    {
        $this->subscribers = [];
    }

    public function subscribe(DomainEventSubscriber $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    public function notify(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function hasEvents(): bool
    {
        return false === empty($this->events);
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }

    public function publish(): void
    {
        array_walk(
            $this->events,
            function (DomainEvent $event) {
                array_walk(
                    array_filter(
                        $this->subscribers,
                        function (DomainEventSubscriber $subscriber) use ($event) {
                            return $subscriber->isSubscribed($event);
                        }
                    ),
                    function (DomainEventSubscriber $subscriber) use ($event) {
                        $subscriber->notify($event);
                    }
                );
            }
        );
    }

    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}