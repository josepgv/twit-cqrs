<?php

declare(strict_types=1);

namespace App\Twit\Domain\Twit\Event;

use App\Twit\Domain\DomainEvent;
use App\Twit\Domain\Twit\Twit;

class TwitWasComposed implements DomainEvent, \JsonSerializable
{
    private function __construct(
        private readonly string $twitId,
        private readonly string $userId,
        private readonly \DateTimeImmutable $occurredOn
    ) {
    }

    public function twitId(): string
    {
        return $this->twitId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public static function fromTwit(Twit $twit): self
    {
        return new self(
            $twit->id()->id(),
            $twit->userId()->id(),
            new \DateTimeImmutable('now')
        );
    }

    public function jsonSerialize(): mixed
    {
        return json_encode(
            [
                'twitId' => $this->twitId,
                'userId' => $this->userId,
                'occurredOn' => $this->occurredOn,
            ]
        );
    }
}
