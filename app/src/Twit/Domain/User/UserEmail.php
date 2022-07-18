<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

use App\Twit\Domain\Common\ValueObject;

class UserEmail extends ValueObject implements \Stringable
{
    private function __construct(private string $email)
    {
        $this->setEmail($this->email);
    }

    public static function fromString(string $email): static
    {
        return new static($email);
    }
    
    public function __toString(): string
    {
        return $this->email();
    }

    private function setEmail(string $email)
    {
        $this->assertEmailIsValid($email);

        $this->email = $email;
    }

    private function assertEmailIsValid(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf("%s is not a valid email address", $email));
        }
    }

    public function email(): string
    {
        return $this->email;
    }
}
