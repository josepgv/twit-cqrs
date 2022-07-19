<?php

declare(strict_types=1);

namespace App\Twit\Application;

use App\Twit\Domain\DomainEvent;

interface EventBusInterface
{
    public function notify(DomainEvent $domainEvent): void;

    /**
     * @param DomainEvent[] $domainEvents
     * @return void
     */
    public function notifyAll(array $domainEvents): void;
}
