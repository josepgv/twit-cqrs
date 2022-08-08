<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

use Doctrine\Common\Collections\ArrayCollection;

interface UserRepositoryInterface
{
    public function ofId(UserId $userId): ?User;

    public function ofNickName(UserNickName $nickName): ?User;

    public function add(User $user): void;

    /**
     * @return ArrayCollection<int, User>
     */
    public function getAll(?int $limit = 0): ArrayCollection;
}
