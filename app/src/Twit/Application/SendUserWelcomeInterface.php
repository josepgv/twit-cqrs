<?php

declare(strict_types=1);

namespace App\Twit\Application;

use App\Twit\Domain\User\UserId;

interface SendUserWelcomeInterface
{
    public function send(UserId $userId): void;
}
