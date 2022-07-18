<?php

namespace App\Tests\Twit\Domain;

use App\Twit\Domain\Common\UuidIdentity;
use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Domain\User\UserWebsite;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase
{
    public function testNormalUserIsCreated(): void
    {
        $uuid = Uuid::uuid4();

        $user = User::signUp(
            UserId::fromUuid($uuid),
            UserNickName::pick('Manolito'),
            UserEmail::fromString('manolito@google.com'),
            'my bio',
            UserWebsite::fromString('https://www.google.com')
        );
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($uuid, $user->userId()->id());
        $this->assertEquals('Manolito', $user->nickName());
        $this->assertEquals('https://www.google.com', $user->website());
        $this->assertEquals('manolito@google.com', $user->email());
        $this->assertEquals('my bio', $user->bio());
    }

    public function testUserWithNullBioAndWebsiteIsCreated(): void
    {
        $uuid = Uuid::uuid4();

        $user = User::signUp(
            UserId::fromUuid($uuid),
            UserNickName::pick('Manolito'),
            UserEmail::fromString('manolito@google.com'),
            null,
            null
        );
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($uuid, $user->userId()->id());
        $this->assertEquals('Manolito', $user->nickName());
        $this->assertEquals('manolito@google.com', $user->email());
    }

    public function testNickNameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $user = User::signUp(
            UserId::fromUuid(Uuid::uuid4()),
            UserNickName::pick(''),
            UserEmail::fromString('manolito@google.com'),
        );
    }

    public function testBioCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $user = User::signUp(
            UserId::fromUuid(Uuid::uuid4()),
            UserNickName::pick('Manolito'),
            UserEmail::fromString('manolito@google.com'),
            ''
        );
    }

    public function testEmailMustBeValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $user = User::signUp(
            UserId::fromUuid(Uuid::uuid4()),
            UserNickName::pick('Manolito'),
            UserEmail::fromString('invalid'),
        );
    }

    public function testWebsiteMustBeValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $user = User::signUp(
            UserId::fromUuid(Uuid::uuid4()),
            UserNickName::pick('Manolito'),
            UserEmail::fromString('manolito@google.com'),
            'my bio',
            UserWebsite::fromString('invalid')
        );
    }
}
