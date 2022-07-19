<?php

namespace App\Tests\Twit\Application\User;

use App\Twit\Application\User\Command\SignUpCommandHandler;
use App\Twit\Domain\User\Event\UserSignedUp;
use App\Twit\Domain\User\UserAlreadyExistsException;
use App\Twit\Domain\User\UserId;
use App\Twit\Infrastructure\Application\InMemoryEventBus;
use App\Twit\Infrastructure\DomainModel\User\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SignUpCommandHandlerTest extends TestCase
{
    public function testUserIsCreated(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventBus       = new InMemoryEventBus();
        $handler        = new SignUpCommandHandler($userRepository, $eventBus);

        $uuid = Uuid::uuid4();
        $handler(new \App\Twit\Application\User\Command\SignUpCommand(
            $uuid,
            'Manolito',
            'manolito@google.com',
            'my bio',
            'https://www.google.com'
        ));

        $user = $userRepository->ofId(UserId::fromString($uuid));
        $this->assertEquals('Manolito', $user->nickName());
    }

    public function testUserWithSameIdCannotBeCreated(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventBus       = new InMemoryEventBus();
        $handler        = new SignUpCommandHandler($userRepository, $eventBus);

        $uuid = Uuid::uuid4();
        $handler(new \App\Twit\Application\User\Command\SignUpCommand(
            $uuid,
            'Manolito',
            'manolito@google.com',
        ));

        $this->expectException(UserAlreadyExistsException::class);
        $handler(new \App\Twit\Application\User\Command\SignUpCommand(
            $uuid,
            'New Manolito',
            'new-manolito@google.com',
        ));
    }

    public function testUserWithSameNickNameCannotBeCreated(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventBus       = new InMemoryEventBus();
        $handler        = new SignUpCommandHandler($userRepository, $eventBus);

        $handler(new \App\Twit\Application\User\Command\SignUpCommand(
            Uuid::uuid4(),
            'Manolito',
            'manolito@google.com',
        ));

        $this->expectException(UserAlreadyExistsException::class);
        $handler(new \App\Twit\Application\User\Command\SignUpCommand(
            Uuid::uuid4(),
            'Manolito',
            'new-manolito@google.com',
        ));
    }

    public function testUserSignedUpEventIsDispatched(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventBus       = new InMemoryEventBus();
        $handler        = new SignUpCommandHandler($userRepository, $eventBus);

        $uuid = Uuid::uuid4();
        $handler(new \App\Twit\Application\User\Command\SignUpCommand(
            $uuid,
            'Manolito',
            'manolito@google.com',
            'my bio',
            'https://www.google.com'
        ));

        $dispatchedEvents = $eventBus->getEvents();
        $this->assertInstanceOf(UserSignedUp::class, $dispatchedEvents[0]);
    }
}
