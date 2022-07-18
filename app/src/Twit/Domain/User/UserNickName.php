<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

use App\Twit\Domain\Common\ValueObject;

class UserNickName extends ValueObject implements \Stringable
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

    private function assertNotEmpty(string $nickName)
    {
        if ('' === $nickName) {
            throw new \InvalidArgumentException("Nickname must not be empty");
        }
    }

    public static function pick(string $userName): static
    {
        return new static($userName);
    }

    public function nickName(): string
    {
        return $this->nickName;
    }

    public function __toString(): string
    {
        return $this->nickName();
    }
}
