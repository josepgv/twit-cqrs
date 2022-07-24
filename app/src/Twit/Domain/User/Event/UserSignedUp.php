<?php

declare(strict_types=1);

namespace App\Twit\Domain\User\Event;

use App\Twit\Domain\DomainEvent;
use App\Twit\Domain\User\User;

final class UserSignedUp implements DomainEvent, \JsonSerializable
{
    private function __construct(
        private readonly string $userId,
        private readonly \DateTimeImmutable $occurredOn
    ) {
    }

    public static function fromUser(User $user): self
    {
        return new self(
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

    public function jsonSerialize(): mixed
    {
        return json_encode([
            'userId' => $this->userId(),
            'occurredOn' => $this->occurredOn(),
        ]);
    }
}
