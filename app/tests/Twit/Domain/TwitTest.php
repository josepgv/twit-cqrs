<?php

declare(strict_types=1);

namespace App\Tests\Twit\Domain;

use App\Twit\Domain\Twit\Event\TwitWasComposed;
use App\Twit\Domain\Twit\Twit;
use App\Twit\Domain\Twit\TwitAlreadyExistsException;
use App\Twit\Domain\Twit\TwitContent;
use App\Twit\Domain\Twit\TwitId;
use App\Twit\Domain\Twit\TwitIsTooLongException;
use App\Twit\Domain\Twit\UserCantTwitException;
use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Domain\User\UserWebsite;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class TwitTest extends TestCase
{
    public const SOME_CONTENT = "This is the content";

    public function testNormalTwitCanBeCreated(): void
    {
        $twitId = TwitId::nextIdentity();
        $userId = UserId::nextIdentity();
        $date = new \DateTimeImmutable('now');
        $user = User::signUp($userId, UserNickName::pick('manolito'), UserEmail::fromString('mano@lito.com'));

        $twit = Twit::compose(
            $twitId,
            $user,
            TwitContent::fromString(self::SOME_CONTENT)
        );

        $this->assertInstanceOf(TwitId::class, $twit->id());
        $this->assertInstanceOf(UserId::class, $twit->userId());
        $this->assertInstanceOf(TwitContent::class, $twit->content());
        $this->assertInstanceOf(\DateTimeImmutable::class, $twit->date());
        $this->assertEquals(self::SOME_CONTENT, $twit->content()->getContent());
        $this->assertEquals(self::SOME_CONTENT, $twit->content());
        $this->assertSame($user, $twit->user());

        $events = $twit->getAndFlushDomainEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(TwitWasComposed::class, $events[0]);
        /** @var TwitWasComposed[] $events */
        $this->assertEquals($twit->id()->id(), $events[0]->twitId());
        $this->assertEquals($twit->userId()->id(), $events[0]->userId());

        /** @var TwitWasComposed $twitWasComposedEvent */
        $twitWasComposedEvent = $events[0];
        $twitWasComposedEventJsonSerialized = $twitWasComposedEvent->jsonSerialize();
        $expectedTwitWasComposedEventJsonSerialized = json_encode(['twitId' => $twit->id()->id(), 'userId' => $twit->userId()->id(), 'occurredOn' => $twitWasComposedEvent->occurredOn()]);
        $this->assertEquals($expectedTwitWasComposedEventJsonSerialized, $twitWasComposedEventJsonSerialized);

        // $this->assertEquals($twit->id()->id(), serialize($events[0])); // wtf?
    }

    public function testLongTwitFails(): void
    {
        $faker = Factory::create('es_ES');
        $content = $faker->realTextBetween(300, 350);

        $this->expectException(TwitIsTooLongException::class);
        Twit::compose(
            TwitId::nextIdentity(),
            UserId::nextIdentity(),
            TwitContent::fromString($content)
        );
    }

    public function testMaxCharsTwitIsComposed(): void
    {
        $faker = Factory::create('es_ES');
        $content = str_repeat('a', TwitContent::MAX_TWIT_LENGTH);

        $twit = Twit::compose(
            TwitId::nextIdentity(),
            User::signUp(
                UserId::nextIdentity(),
                UserNickName::pick('manolito'),
                UserEmail::fromString('a@b.com'),
                '...',
                UserWebsite::fromString('https://google.com')
            ),
            TwitContent::fromString($content)
        );

        $this->assertEquals($content, $twit->content()->getContent());
    }

    public function testTwitExistsException(): void
    {
        $this->expectException(TwitAlreadyExistsException::class);
        $twitId = TwitId::nextIdentity();
        $this->expectExceptionMessage(sprintf('Twit with %s "%s" already exists', 'id', $twitId->id()));
        throw TwitAlreadyExistsException::withIdOf($twitId);
    }

    public function testUserCantTwitException(): void
    {
        $this->expectException(UserCantTwitException::class);
        $userId = UserId::nextIdentity();
        $this->expectExceptionMessage(sprintf('User with ID "%s"  cant twit because %s', $userId->id(), 'somereason'));

        throw UserCantTwitException::withIdOf($userId, 'somereason');

    }
}
