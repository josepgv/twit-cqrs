<?php

declare(strict_types=1);

namespace App\Twit\Domain\Twit;

class TwitAlreadyExistsException extends \RuntimeException
{
    private function __construct(string $value, string $fieldName)
    {
        parent::__construct(
            sprintf('Twit with %s "%s" already exists', $fieldName, $value)
        );
    }

    public static function withIdOf(TwitId $twitId): self
    {
        return new self($twitId->id(), 'id');
    }
}
