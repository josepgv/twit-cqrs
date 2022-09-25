<?php

declare(strict_types=1);

namespace App\Twit\Domain\Twit;

use App\Twit\Domain\User\UserId;

class UserCantTwitException extends \RuntimeException
{
    private function __construct(string $value, string $reason)
    {
        parent::__construct(
            sprintf('User with ID "%s"  cant twit because %s', $value, $reason)
        );
    }

    public static function withIdOf(UserId $userId, string $reason): self
    {
        return new self($userId->id(), $reason);
    }
}
