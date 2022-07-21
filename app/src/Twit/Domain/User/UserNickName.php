<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

use App\Twit\Domain\Common\ValueObject;

final class UserNickName extends ValueObject implements \Stringable
{
    private function __construct(private string $nickName)
    {
        $this->setNickName($this->nickName);
    }

    private function setNickName(string $nickName): void
    {
        $this->assertNotEmpty($nickName);
        $this->nickName = $nickName;
    }

    private function assertNotEmpty(string $nickName): void
    {
        if ('' === $nickName) {
            throw new \InvalidArgumentException("Nickname must not be empty");
        }
    }

    public static function pick(string $userName): self
    {
        return new self($userName);
    }

    public function nickName(): string
    {
        return $this->nickName;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private function toString(): string
    {
        return $this->nickName;
    }
}
