<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

class User
{
    public function __construct(
        protected string $authorId,
        protected string $nickName,
        protected string $email,
        protected ?string $bio = null,
        protected ?string $website = null,
    ) {

    }

    /*public static function signUp(): static
    {
        return new static();
    }*/
}
