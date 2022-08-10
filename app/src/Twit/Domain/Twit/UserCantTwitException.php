<?php

declare(strict_types=1);

namespace App\Twit\Domain\Twit;

class UserCantTwitException extends \RuntimeException
{
    private function __construct(string $value, string $reason)
    {
        parent::__construct(
            sprintf('User with %s "%s" cant twit because ', $reason, $value)
        );
    }

    public static function withIdOf(TwitId $twitId, string $reason): self
    {
        return new self($twitId->id(), $reason);
    }
}
