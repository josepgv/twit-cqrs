<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\Application;

use App\Twit\Application\CommandBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyCommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function handle(object $command): Envelope
    {
        try {
            $envelope = $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $exception) {
            throw $exception->getNestedExceptions()[0];
        }

        return $envelope;
    }
}
