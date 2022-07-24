<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\DomainModel\EventStore;

use App\Twit\Domain\EventStore\EventStore;
use App\Twit\Domain\EventStore\EventStoreRepositoryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Serializer\SerializerInterface;

class InMemoryEventStoreRepository implements EventStoreRepositoryInterface
{
    /** @var array<int, EventStore> */
    private array $events = [];

    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function append(Envelope $event): void
    {
        $store = new EventStore(
            (new \ReflectionClass($event->getMessage()))->getShortName(),
            $this->serializer->serialize($event->getMessage(), 'json'),
            new \DateTimeImmutable('now')
        );
        $this->events[] = $store;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
