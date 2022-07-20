<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Projection;

class IncrementTotalUsersProjectionHandler
{
    public function __construct(private readonly \Redis $redis)
    {
    }

    public function __invoke(IncrementTotalUsersProjection $projection): void
    {
        $this->redis->hIncrBy(
            'total_users',
            'amount',
            1
        );
        $this->redis->hSet(
            'total_users',
            'last_updated',
            $projection->getOccurredOn()->format('d/m/Y H:i:s')
        );
    }
}
