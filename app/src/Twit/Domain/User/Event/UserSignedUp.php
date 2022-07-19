<?php

declare(strict_types=1);

namespace App\Twit\Domain\User\Event;

use App\Twit\Domain\DomainEvent;
use App\Twit\Domain\User\User;

class UserSignedUp implements DomainEvent
{
    private function __construct(
        private readonly string $userId,
        private readonly \DateTimeImmutable $occurredOn
    ) {
    }

    public static function fromUser(User $user): static
    {
        return new static(
            $user->userId()->id(),
            new \DateTimeImmutable('now')
        );
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
