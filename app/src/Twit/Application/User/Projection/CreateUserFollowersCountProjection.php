<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Projection;

use App\Twit\Application\Projection;
use App\Twit\Domain\User\UserId;

class CreateUserFollowersCountProjection implements Projection
{
    private \DateTimeImmutable $updatedAt;

    public function __construct(private readonly UserId $userId)
    {
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
