<?php

declare(strict_types=1);

namespace App\Tests\Twit\Application\User;

use App\Twit\Application\Projection;
use App\Twit\Application\ProjectionBusInterface;
use App\Twit\Application\SendUserWelcomeInterface;
use App\Twit\Application\User\Event\UserSignedUpEventHandler;
use App\Twit\Application\User\Projection\CreateUserFollowersCountProjection;
use App\Twit\Application\User\Projection\IncrementTotalUsersProjection;
use App\Twit\Domain\User\Event\UserSignedUp;
use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Infrastructure\Application\InMemoryProjectionBus;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class UserSignedUpEventHandlerTest extends TestCase
{
    public function testUserSignedUpEventHandlerDispatchesExpectedProjections(): void
    {
        $userId = UserId::nextIdentity();

        $projectionBus = new InMemoryProjectionBus();

        $expectedProjectionCalls = [
            IncrementTotalUsersProjection::class => [
                'expected_calls' => 1,
                'actual_calls' => 0
            ],
            CreateUserFollowersCountProjection::class => [
                'expected_calls' => 1,
                'actual_calls' => 0
            ]
        ];

        $event  = UserSignedUp::fromUser(
            User::signUp(
                $userId,
                UserNickName::pick('manolito'),
                UserEmail::fromString('mano@lito.com')
            )
        );

        $eventJsonSerialized = $event->jsonSerialize();
        $expectedEventJsonSerialized = json_encode(['userId' => $userId->id(), 'occurredOn' => $event->occurredOn()]);
        $this->assertEquals($expectedEventJsonSerialized, $eventJsonSerialized);

        $logger          = new FakeLogger();
        $sendUserWelcome = new FakeSendUserWelcome();
        $handler         = new UserSignedUpEventHandler(
            $logger,
            $projectionBus,
            $sendUserWelcome
        );

        $handler($event);

        $projectionsCounter = $projectionBus->getProjectionsCounter();
        foreach ($expectedProjectionCalls as $projectionClass => $expectations) {
            $this->assertEquals($expectations['expected_calls'], $projectionsCounter[$projectionClass]);
        }


        $this->assertEquals(1, $logger->timesInfoCalled);
        $this->assertEquals(true, $sendUserWelcome->wasSent());
    }

    public function testInMemoryProjectionBusCountsProjectionsCorrectly(): void
    {
        $projectionBus = new InMemoryProjectionBus();
        $projection1 = new IncrementTotalUsersProjection();
        $projection2 = new IncrementTotalUsersProjection();
        $projectionBus->project($projection1);
        $projectionBus->project($projection2);
        $projectionsCounter = $projectionBus->getProjectionsCounter();

        $this->assertEquals(2, $projectionsCounter[IncrementTotalUsersProjection::class]);
        $this->assertInstanceOf(IncrementTotalUsersProjection::class, $projectionBus->getProjections()[IncrementTotalUsersProjection::class]);
    }
}

class FakeSendUserWelcome implements SendUserWelcomeInterface
{
    private bool $sent = false;
    public function send(UserId $userId): void
    {
        $this->sent = true;
    }

    public function wasSent(): bool
    {
        return $this->sent;
    }
}

class FakeLogger implements LoggerInterface
{
    public int $timesInfoCalled = 0;
    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->timesInfoCalled++;
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        // TODO: Implement emergency() method.
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        // TODO: Implement alert() method.
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        // TODO: Implement critical() method.
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        // TODO: Implement error() method.
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        // TODO: Implement warning() method.
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        // TODO: Implement notice() method.
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        // TODO: Implement debug() method.
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        // TODO: Implement log() method.
    }
}
