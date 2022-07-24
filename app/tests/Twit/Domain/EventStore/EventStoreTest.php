<?php

declare(strict_types=1);

namespace App\Tests\Twit\Domain\EventStore;

use App\Twit\Domain\DomainEvent;
use App\Twit\Domain\EventStore\EventStore;
use App\Twit\Domain\User\Event\UserSignedUp;
use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Infrastructure\DomainModel\EventStore\InMemoryEventStoreRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Serializer\SerializerInterface;

class EventStoreTest extends KernelTestCase
{
    public function testEventStoreAppendsCorrectly(): void
    {
        $container = static::getContainer();
        $userId = UserId::nextIdentity();
        $message = UserSignedUp::fromUser(
            User::signUp(
                $userId,
                UserNickName::pick('manolito'),
                UserEmail::fromString('mano@lito.com')
            )
        );

        $message2 = new \stdClass();
        $message2->content = 'The content 2';

        $serializer = $container->get(SerializerInterface::class);
        $eventStore = new InMemoryEventStoreRepository($serializer);
        $eventStore->append(new Envelope($message));
        $eventStore->append(new Envelope($message2));

        $events = $eventStore->getEvents();

        $this->assertCount(2, $events);

        $this->assertInstanceOf(EventStore::class, $events[0]);
        $this->assertInstanceOf(EventStore::class, $events[1]);

        $this->assertEquals((new \ReflectionClass(UserSignedUp::class))->getShortName(), $events[0]->type());
        $this->assertInstanceOf(UserSignedUp::class, $serializer->deserialize($events[0]->event(), UserSignedUp::class, 'json'));
        $this->assertEquals(null, $events[0]->id());
        $this->assertInstanceOf(\DateTimeImmutable::class, $events[0]->ocurredOn());

    }
}
