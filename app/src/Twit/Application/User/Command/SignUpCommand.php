<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Command;

use App\Twit\Application\Command;

class SignUpCommand implements Command
{
    public function __construct(
        public readonly string $userId,
        public readonly string $nickName,
        public readonly string $email,
        public readonly ?string $bio = null,
        public readonly ?string $website = null,
    ) {
    }
}
