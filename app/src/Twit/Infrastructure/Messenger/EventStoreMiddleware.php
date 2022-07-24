<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\Messenger;

use App\Twit\Domain\EventStore\EventStoreRepositoryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Serializer\SerializerInterface;

class EventStoreMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly SerializerInterface $serializer, private readonly EventStoreRepositoryInterface $eventStoreRepository)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            $this->eventStoreRepository->append($envelope);
        } catch (\Exception $exception) {
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
