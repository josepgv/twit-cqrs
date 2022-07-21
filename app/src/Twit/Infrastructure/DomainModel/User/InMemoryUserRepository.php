<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\DomainModel\User;

use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Domain\User\UserRepositoryInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var User[]  */
    private array $users = [];

    public function ofId(UserId $userId): ?User
    {
        return $this->users[$userId->id()] ?? null;
    }

    public function ofNickName(UserNickName $nickName): ?User
    {
        foreach ($this->users as $user) {
            if ($user->nickName()->nickName() === $nickName->nickName()) {
                return $user;
            }
        }
        return null;
    }

    public function add(User $user): void
    {
        if (null === $this->ofId($user->userId())) {
            $this->users[$user->userId()->id()] = $user;
        }
    }
}
