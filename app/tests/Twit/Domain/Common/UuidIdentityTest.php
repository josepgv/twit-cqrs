<?php

declare(strict_types=1);

namespace App\Tests\Twit\Domain\Common;

use App\Twit\Domain\Common\UuidIdentity;
use App\Twit\Domain\User\UserId;
use PHPUnit\Framework\TestCase;

class UuidIdentityTest extends TestCase
{
    public function testInvalidUuidStringThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        UuidIdentity::fromString('manolito');
    }

    public function testItCanBeConvertedToString(): void
    {
        $identity = ClassWithUuidIdentity::nextIdentity();
        $this->assertIsString((string)$identity);
    }
}

class ClassWithUuidIdentity extends UuidIdentity
{
}
