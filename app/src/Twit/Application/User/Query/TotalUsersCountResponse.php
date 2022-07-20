<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Query;

class TotalUsersCountResponse
{
    public function __construct(
        public readonly int $amount,
        public readonly \DateTimeImmutable $last_updated
    ) {
    }
}
