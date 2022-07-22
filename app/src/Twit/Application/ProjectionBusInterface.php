<?php

declare(strict_types=1);

namespace App\Twit\Application;

interface ProjectionBusInterface
{
    public function project(Projection $projection): void;
}
