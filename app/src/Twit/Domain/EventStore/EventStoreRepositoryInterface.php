<?php

declare(strict_types=1);

namespace App\Twit\Domain\EventStore;

use Symfony\Component\Messenger\Envelope;

interface EventStoreRepositoryInterface
{
    public function append(Envelope $event): void;
}
