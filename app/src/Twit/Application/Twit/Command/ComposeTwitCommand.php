<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Command;

use App\Twit\Application\Command;
use App\Twit\Domain\User\User;

class ComposeTwitCommand implements Command
{
    public function __construct(
        public readonly string $twitId,
        public readonly User $user,
        public readonly string $content
    ) {
    }
}
