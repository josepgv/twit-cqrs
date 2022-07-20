<?php

declare(strict_types=1);

namespace App\Tests\Twit\Application\User\Projection;

use App\Twit\Application\User\Projection\IncrementTotalUsersProjection;
use App\Twit\Application\User\Projection\IncrementTotalUsersProjectionHandler;
use PHPUnit\Framework\TestCase;

class IncrementTotalUsersProjectionHandlerTest extends TestCase
{
    public function testTotalUsersProjectionIsIncremented(): void
    {

        $mockRedis = $this->createMock(\Redis::class);

        $mockRedis->expects($this->once())
            ->method('hIncrBy')
            ->with(
                'total_users',
                'amount',
                1
            );

        $mockRedis->expects($this->once())
            ->method('hSet')
            ->with('total_users', 'last_updated', (new \DateTimeImmutable('now'))->format('d/m/Y H:i:s'));


        $projection = new IncrementTotalUsersProjection();
        $projector = new IncrementTotalUsersProjectionHandler($mockRedis);
        $projector($projection);
    }
}
