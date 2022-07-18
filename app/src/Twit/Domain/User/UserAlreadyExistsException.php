<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

class UserAlreadyExistsException extends \RuntimeException
{
    private function __construct(string $value, string $fieldName)
    {
        parent::__construct(
            sprintf('Author with %s "%s" already exists', $fieldName, $value)
        );
    }

    public static function withUserNickNameOf(UserNickName $userNickName): self
    {
        return new self($userNickName->nickName(), 'nick name');
    }

    public static function withIdOf(UserId $user): self
    {
        return new self($user->id(), 'id');
    }
}
