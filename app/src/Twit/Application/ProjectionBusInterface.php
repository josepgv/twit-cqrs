<?php

declare(strict_types=1);

namespace App\Twit\Application;

use App\Twit\Domain\DomainEvent;

interface ProjectionBusInterface
{
    public function project(Projection $projection): void;
}
