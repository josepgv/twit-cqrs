<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Query;

class TimelineTwitResponse
{
    public function __construct(
        public readonly string $twitId,
        public readonly string $userid,
        public readonly string $username,
        public readonly string $content,
        public readonly \DateTimeImmutable $date
    ) {
    }
}
