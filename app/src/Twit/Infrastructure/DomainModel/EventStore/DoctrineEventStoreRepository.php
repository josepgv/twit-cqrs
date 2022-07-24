<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\DomainModel\EventStore;

use App\Twit\Domain\EventStore\EventStore;
use App\Twit\Domain\EventStore\EventStoreRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Serializer\SerializerInterface;

class DoctrineEventStoreRepository implements EventStoreRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SerializerInterface $serializer)
    {
    }

    public function append(Envelope $event): void
    {
        $store = new EventStore(
            (new \ReflectionClass($event->getMessage()))->getShortName(),
            $this->serializer->serialize($event->getMessage(), 'json'),
            new \DateTimeImmutable('now')
        );
        $this->entityManager->persist($store);
        $this->entityManager->flush();
    }
}
