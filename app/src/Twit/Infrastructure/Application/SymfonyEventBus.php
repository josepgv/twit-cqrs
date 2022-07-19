<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\Application;

use App\Twit\Application\EventBusInterface;
use App\Twit\Domain\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyEventBus implements EventBusInterface
{
    public function __construct(private readonly MessageBusInterface $eventBus)
    {
    }

    public function notify(DomainEvent $domainEvent): void
    {
        $this->eventBus->dispatch($domainEvent);
    }

    public function notifyAll(array $domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->notify($domainEvent);
        }
    }
}
