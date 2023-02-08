<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Query;

use App\Twit\Domain\Twit\TwitContent;
use App\Twit\Domain\Twit\TwitId;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;

class TimelineTwitResponse
{
    public function __construct(
        public readonly TwitId $twitId,
        public readonly UserId $userid,
        public readonly UserNickName $username,
        public readonly TwitContent $content,
        public readonly \DateTimeImmutable $date
    ) {
    }
}
