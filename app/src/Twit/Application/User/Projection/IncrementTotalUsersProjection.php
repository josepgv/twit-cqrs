<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Projection;

use App\Twit\Application\Projection;

class IncrementTotalUsersProjection implements Projection
{
    private \DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new \DateTimeImmutable('now');
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
