<?php

declare(strict_types=1);

namespace App\Twit\Domain\Common;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class UuidIdentity extends ValueObject implements \Stringable
{
    final private function __construct(
        protected string $id
    ) {
    }

    public static function nextIdentity(): static
    {
        return new static(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): static
    {
        if (!Uuid::isValid($id)) {
            throw new \InvalidArgumentException(sprintf("The value %s is not a valid Uuid", $id));
        }
        return new static($id);
    }

    public static function fromUuid(UuidInterface $uuid): static
    {
        return new static($uuid->toString());
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
