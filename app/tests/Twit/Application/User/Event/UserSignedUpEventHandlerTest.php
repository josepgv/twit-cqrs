<?php

declare(strict_types=1);

namespace App\Tests\Twit\Application\User;

use App\Twit\Application\Projection;
use App\Twit\Application\ProjectionBusInterface;
use App\Twit\Application\User\Event\UserSignedUpEventHandler;
use App\Twit\Application\User\Projection\CreateUserFollowersCountProjection;
use App\Twit\Application\User\Projection\IncrementTotalUsersProjection;
use App\Twit\Domain\User\Event\UserSignedUp;
use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class UserSignedUpEventHandlerTest extends TestCase
{
    public function testUserSignedUpEventHandlerDispatchesExpectedProjections(): void
    {
        $mockProjectionBus = $this->createMock(ProjectionBusInterface::class);

        $expectedProjections = [
            0 => IncrementTotalUsersProjection::class,
            1 => CreateUserFollowersCountProjection::class
        ];

        foreach ($expectedProjections as $index => $expectedProjectionClass) {
            $mockProjectionBus->expects($this->at($index))
                ->method('project')
                ->with(self::callback(function (Projection $projection) use ($expectedProjectionClass) {
                    return $projection::class === $expectedProjectionClass;
                }));
        }

        $event = UserSignedUp::fromUser(
            User::signUp(
                UserId::nextIdentity(),
                UserNickName::pick('manolito'),
                UserEmail::fromString('mano@lito.com')
            )
        );

        $handler = new UserSignedUpEventHandler(
            new FakeLogger(),
            $mockProjectionBus
        );

        $handler($event);
    }
}

class FakeLogger implements LoggerInterface
{
    public function info(\Stringable|string $message, array $context = []): void
    {
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
