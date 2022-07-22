<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

interface UserRepositoryInterface
{
    public function ofId(UserId $userId): ?User;

    public function ofNickName(UserNickName $nickName): ?User;

    public function add(User $user): void;
}
