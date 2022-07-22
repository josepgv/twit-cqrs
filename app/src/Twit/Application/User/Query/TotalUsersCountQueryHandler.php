<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Query;

class TotalUsersCountQueryHandler
{
    public function __construct(private readonly \Redis $redis)
    {
    }

    public function __invoke(TotalUsersCountQuery $query): TotalUsersCountResponse
    {
        $total_users = $this->redis->hGetAll('total_users');

        if (!$total_users) {
            return new TotalUsersCountResponse(0, new \DateTimeImmutable('now'));
        }

        $last_updated = \DateTimeImmutable::createFromFormat('d/m/Y H:i:s', $total_users['last_updated']);
        if (!$last_updated) {
            $last_updated = new \DateTimeImmutable('now');
        }

        return new TotalUsersCountResponse(
            intval($total_users['amount']),
            $last_updated
        );
    }
}
