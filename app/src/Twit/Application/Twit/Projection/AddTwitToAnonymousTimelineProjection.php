<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Projection;

use App\Twit\Application\Projection;
use App\Twit\Domain\Twit\Twit;

class AddTwitToAnonymousTimelineProjection implements Projection
{
    public function __construct(private readonly Twit $twit)
    {
    }

    public function getTwit(): Twit
    {
        return $this->twit;
    }
}
