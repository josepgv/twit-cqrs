<?php

namespace App\Tests\Twit\Application\User\Command;

use App\Tests\Twit\Domain\UserTest;
use App\Twit\Application\User\Command\SignUpCommand;
use App\Twit\Application\User\Command\SignUpCommandHandler;
use App\Twit\Domain\DomainEvent;
use App\Twit\Domain\User\Event\UserSignedUp;
use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserAlreadyExistsException;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
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
        $handler(
            new SignUpCommand(
                $uuid,
                'Manolito',
                UserTest::MANOLITO_AT_GOOGLE_COM,
                'my bio',
                'https://www.google.com'
            )
        );

        $user = $userRepository->ofId(UserId::fromString($uuid->toString()));
        $this->assertNotNull($user);
        $this->assertEquals('Manolito', $user->nickName());

        $this->assertEquals($user->userId()->id(), $eventBus->getEvents()[0]->userId());
    }

    public function testUserWithSameIdCannotBeCreated(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventBus       = new InMemoryEventBus();
        $handler        = new SignUpCommandHandler($userRepository, $eventBus);

        $uuid = Uuid::uuid4();
        $handler(
            new SignUpCommand(
                $uuid->toString(),
                'Manolito',
                UserTest::MANOLITO_AT_GOOGLE_COM,
            )
        );

        $this->expectException(UserAlreadyExistsException::class);
        $handler(
            new SignUpCommand(
                $uuid->toString(),
                'New Manolito',
                'new-manolito@google.com',
            )
        );
    }

    public function testUserWithSameNickNameCannotBeCreated(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventBus       = new InMemoryEventBus();
        $handler        = new SignUpCommandHandler($userRepository, $eventBus);

        $handler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                'Manolito',
                UserTest::MANOLITO_AT_GOOGLE_COM,
            )
        );

        $this->expectException(UserAlreadyExistsException::class);
        $this->expectExceptionMessage('Author with nick name "Manolito" already exists');
        $handler(
            new SignUpCommand(
                Uuid::uuid4()->toString(),
                'Manolito',
                'new-manolito@google.com',
            )
        );
    }

    public function testUserSignedUpEventIsDispatched(): void
    {
        $userRepository = new InMemoryUserRepository();
        $eventBus       = new InMemoryEventBus();
        $handler        = new SignUpCommandHandler($userRepository, $eventBus);

        $uuid = Uuid::uuid4();
        $handler(
            new SignUpCommand(
                $uuid,
                'Manolito',
                UserTest::MANOLITO_AT_GOOGLE_COM,
                'my bio',
                'https://www.google.com'
            )
        );

        $dispatchedEvents = $eventBus->getEvents();

        /** @var UserSignedUp $domainEvent */
        $domainEvent = $dispatchedEvents[0];
        $this->assertInstanceOf(UserSignedUp::class, $domainEvent);
        $this->assertSame($uuid->toString(), $domainEvent->userId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $domainEvent->occurredOn());
    }

    public function testUserCanHaveMultipleDomainEvents(): void
    {
        $user = new UserWithMultipleDomainEvents(
            UserId::nextIdentity()->id(),
            UserNickName::pick('Manolito')->nickName(),
            UserEmail::fromString('mano@lito.com')->email()
        );
        $user->addMultipleDomainEvents();
        $events = $user->getAndFlushDomainEvents();
        $this->assertGreaterThan(1, count($events));
    }
}

class UserWithMultipleDomainEvents extends User
{
    public function addMultipleDomainEvents(): void
    {
        $anEvent = UserSignedUp::fromUser($this);
        $this->addDomainEvent($anEvent);
        $this->addDomainEvent($anEvent);
    }
}

class FakeDomainEvent implements DomainEvent
{

}
