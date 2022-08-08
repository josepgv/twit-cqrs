<?php

declare(strict_types=1);

namespace App\Tests\Twit\Domain;

use App\Twit\Domain\Twit\Event\TwitWasComposed;
use App\Twit\Domain\Twit\Twit;
use App\Twit\Domain\Twit\TwitContent;
use App\Twit\Domain\Twit\TwitId;
use App\Twit\Domain\Twit\TwitIsTooLongException;
use App\Twit\Domain\User\UserId;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class TwitTest extends TestCase
{
    public function testNormalTwitCanBeCreated(): void
    {
        $twitId = TwitId::nextIdentity();
        $userId = UserId::nextIdentity();
        $date = new \DateTimeImmutable('now');

        $twit = Twit::compose(
            $twitId,
            $userId,
            TwitContent::fromString("This is the content")
        );

        $this->assertInstanceOf(TwitId::class, $twit->id());
        $this->assertInstanceOf(UserId::class, $twit->userId());
        $this->assertInstanceOf(TwitContent::class, $twit->content());
        $this->assertInstanceOf(\DateTimeImmutable::class, $twit->date());
        $this->assertEquals("This is the content", $twit->content()->getContent());
        $this->assertEquals("This is the content", $twit->content());

        $events = $twit->getAndFlushDomainEvents();
        $this->assertInstanceOf(TwitWasComposed::class, $events[0]);
        /** @var TwitWasComposed[] $events */
        $this->assertEquals($twit->id()->id(), $events[0]->twitId());
        $this->assertEquals($twit->userId()->id(), $events[0]->userId());
        
        $this->assertEquals($twit->id()->id(), serialize($events[0]));
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
}
