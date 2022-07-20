<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\Application;

use App\Twit\Application\Projection;
use App\Twit\Application\ProjectionBusInterface;
use App\Twit\Domain\DomainEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class SymfonyProjectionBus implements ProjectionBusInterface
{
    public function __construct(private readonly MessageBusInterface $projectionBus)
    {
    }


    /**
     * @throws Throwable
     */
    public function project(Projection $projection): void
    {
        try {
            $this->projectionBus->dispatch($projection);
        } catch (HandlerFailedException $exception) {
            throw $exception->getNestedExceptions()[0];
        }
    }
}
