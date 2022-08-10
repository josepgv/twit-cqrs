<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Command;

use App\Twit\Application\Command;

class ComposeTwitCommand implements Command
{
    public function __construct(
        public readonly string $twitId,
        public readonly string $userId,
        public readonly string $content
    ) {
    }
}
