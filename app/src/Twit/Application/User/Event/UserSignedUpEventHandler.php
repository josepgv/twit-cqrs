<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Event;

use App\Twit\Domain\User\Event\UserSignedUp;
use Psr\Log\LoggerInterface;

class UserSignedUpEventHandler
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(UserSignedUp $event): void
    {
        $this->logger->info(sprintf("User %s signed up on %s", $event->userId(), $event->occurredOn()->format('d/m/Y H:i:s')));
    }
}
