<?php

declare(strict_types=1);

namespace App\Tests\Twit\Application\User\Query;

use App\Twit\Application\User\Query\TotalUsersCountQuery;
use App\Twit\Application\User\Query\TotalUsersCountQueryHandler;
use App\Twit\Application\User\Query\TotalUsersCountResponse;
use PHPUnit\Framework\TestCase;

class TotalUsersCountQueryHandlerTest extends TestCase
{
    public function testItReturnsWithTotalUsersCountResponseWhenRedisResponseIsNull(): void
    {
        $redisMock = $this->createMock(\Redis::class);

        $queryHandler = new TotalUsersCountQueryHandler($redisMock);

        $queryResult = $queryHandler(new TotalUsersCountQuery());

        $this->assertInstanceOf(TotalUsersCountResponse::class, $queryResult);
        $this->assertEquals(0, $queryResult->amount);
        //$this->assertEquals((new \DateTimeImmutable('now')), $queryResult->last_updated); //@todo microseconds issue
    }

    public function testItReturnsWithTotalUsersCountResponseWhenRedisResponseIsNotNull(): void
    {
        $redisMock = $this->createMock(\Redis::class);

        $redisMock->method('hGetAll')->willReturn([
            'amount' => 7,
            'last_updated' => (new \DateTimeImmutable('today'))->format('d/m/Y H:i:s')
            ]);

        $queryHandler = new TotalUsersCountQueryHandler($redisMock);

        $queryResult = $queryHandler(new TotalUsersCountQuery());

        $this->assertInstanceOf(TotalUsersCountResponse::class, $queryResult);
        $this->assertEquals(7, $queryResult->amount);
        $this->assertEquals((new \DateTimeImmutable('today')), $queryResult->last_updated);
    }

    public function testItReturnsWithTotalUsersCountResponseWhenRedisResponseContainsInvalidLastUpdatedDate(): void
    {
        $redisMock = $this->createMock(\Redis::class);
        $redisMock->method('hGetAll')->willReturn([
            'amount' => 7,
            'last_updated' => 'not-a-valid-date'
        ]);

        $queryHandler = new TotalUsersCountQueryHandler($redisMock);

        $queryResult = $queryHandler(new TotalUsersCountQuery());

        $this->assertInstanceOf(TotalUsersCountResponse::class, $queryResult);
        $this->assertEquals(7, $queryResult->amount);
        $this->assertInstanceOf(\DateTimeImmutable::class, $queryResult->last_updated);
        //$this->assertEquals((new \DateTimeImmutable('now')), $queryResult->last_updated); //@todo microseconds issue
    }
}
