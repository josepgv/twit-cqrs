<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Projection;

use Redis;

class CreateUserFollowersCountProjectionHandler
{
    public const USER_FOLLOWERS_COUNT_PREFIX = 'user_follower_count';

    public function __construct(private readonly Redis $redis)
    {
    }

    public function __invoke(CreateUserFollowersCountProjection $projection): void
    {
        $this->redis->hMSet(
            sprintf("%s:%s", self::USER_FOLLOWERS_COUNT_PREFIX, $projection->getUserId()->id()),
            [
                'id' => $projection->getUserId()->id(),
                'follower_count' => 0
            ]
        );
    }
}
