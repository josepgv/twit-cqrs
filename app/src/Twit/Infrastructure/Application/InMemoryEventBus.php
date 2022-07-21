<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\Application;

use App\Twit\Application\EventBusInterface;
use App\Twit\Domain\DomainEvent;

class InMemoryEventBus implements EventBusInterface
{
    /** @var DomainEvent[]  */
    private array $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function notify(DomainEvent $domainEvent): void
    {
        $this->events[] = $domainEvent;
    }

    public function notifyAll(array $domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->notify($domainEvent);
        }
    }

    /**
     * @return DomainEvent[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}
