<?php

declare(strict_types=1);

namespace App\Twit\Domain\Twit;

use App\Twit\Domain\User\UserId;
use Doctrine\Common\Collections\ArrayCollection;

interface TwitRepositoryInterface
{
    public function ofId(TwitId $twitId): ?Twit;

    /**
     * @param UserId $userId
     * @return ArrayCollection
     */
    public function ofUserId(UserId $userId): ArrayCollection;

    public function add(Twit $twit): void;
}
