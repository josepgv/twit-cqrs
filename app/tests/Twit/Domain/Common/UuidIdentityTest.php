<?php

declare(strict_types=1);

namespace App\Tests\Twit\Domain\Common;

use App\Twit\Domain\Common\UuidIdentity;
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
        $identity = UuidIdentity::nextIdentity();
        $this->assertIsString((string)$identity);
    }
}
