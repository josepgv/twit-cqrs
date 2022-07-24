<?php

namespace App\Twit\Domain\EventStore;

class EventStore
{
    protected ?int $id = null;

    public function __construct(protected string $type, protected string $event, protected \DateTimeImmutable $ocurredOn)
    {
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function event(): string
    {
        return $this->event;
    }

    public function ocurredOn(): \DateTimeImmutable
    {
        return $this->ocurredOn;
    }
}
