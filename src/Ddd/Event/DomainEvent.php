<?php
namespace Jgrc\Bootstrap\Ddd\Event;

interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
    public function __toArray(): array;
}