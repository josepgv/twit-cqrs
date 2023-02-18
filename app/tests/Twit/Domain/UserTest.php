<?php

namespace App\Tests\Twit\Domain;

use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Domain\User\UserWebsite;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase
{
    public const MANOLITO_AT_GOOGLE_COM = 'manolito@google.com';
    public const MY_BIO = 'my bio';

    public function testNormalUserIsCreated(): void
    {
        $uuid = Uuid::uuid4();

        $user = User::signUp(
            UserId::fromUuid($uuid),
            UserNickName::pick('Manolito'),
            UserEmail::fromString(self::MANOLITO_AT_GOOGLE_COM),
            self::MY_BIO,
            UserWebsite::fromString('https://www.google.com')
        );
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($uuid, $user->userId()->id());
        $this->assertEquals('Manolito', $user->nickName());
        $this->assertEquals('https://www.google.com', $user->website());
        $this->assertEquals(self::MANOLITO_AT_GOOGLE_COM, $user->email());
        $this->assertEquals(self::MY_BIO, $user->bio());
    }

    public function testUserWithNullBioAndWebsiteIsCreated(): void
    {
        $uuid = Uuid::uuid4();

        $user = User::signUp(
            UserId::fromUuid($uuid),
            UserNickName::pick('Manolito'),
            UserEmail::fromString(self::MANOLITO_AT_GOOGLE_COM),
            null,
            null
        );
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($uuid, $user->userId()->id());
        $this->assertEquals('Manolito', $user->nickName());
        $this->assertEquals(self::MANOLITO_AT_GOOGLE_COM, $user->email());
    }

    public function testNickNameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $user = User::signUp(
            UserId::fromUuid(Uuid::uuid4()),
            UserNickName::pick(''),
            UserEmail::fromString(self::MANOLITO_AT_GOOGLE_COM),
        );
    }

    public function testBioCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $user = User::signUp(
            UserId::fromUuid(Uuid::uuid4()),
            UserNickName::pick('Manolito'),
            UserEmail::fromString(self::MANOLITO_AT_GOOGLE_COM),
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
            UserEmail::fromString(self::MANOLITO_AT_GOOGLE_COM),
            self::MY_BIO,
            UserWebsite::fromString('invalid')
        );
    }
}
