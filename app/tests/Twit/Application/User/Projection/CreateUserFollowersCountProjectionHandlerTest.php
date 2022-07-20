<?php

declare(strict_types=1);

namespace App\Tests\Twit\Application\User\Projection;

use App\Twit\Application\User\Projection\CreateUserFollowersCountProjection;
use App\Twit\Application\User\Projection\CreateUserFollowersCountProjectionHandler;
use App\Twit\Application\User\Projection\IncrementTotalUsersProjection;
use App\Twit\Application\User\Projection\IncrementTotalUsersProjectionHandler;
use App\Twit\Domain\User\UserId;
use PHPUnit\Framework\TestCase;

class CreateUserFollowersCountProjectionHandlerTest extends TestCase
{
    public function testUserFollowersCountProjectionIsCreated(): void
    {
        $mockRedis = $this->createMock(\Redis::class);

        $userId = UserId::nextIdentity();

        $mockRedis->expects($this->once())
            ->method('hMSet')
            ->with(
                sprintf("%s:%s", CreateUserFollowersCountProjectionHandler::USER_FOLLOWERS_COUNT_PREFIX, $userId->id()),
                [
                    'id' => $userId->id(),
                    'follower_count' => 0
                ]
            );

        $projection = new CreateUserFollowersCountProjection($userId);
        $projector = new CreateUserFollowersCountProjectionHandler($mockRedis);
        $projector($projection);
        
        $this->assertInstanceOf(\DateTimeImmutable::class, $projection->getUpdatedAt());
    }
}
