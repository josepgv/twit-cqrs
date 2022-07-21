<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\Application;

use App\Twit\Application\Projection;
use App\Twit\Application\ProjectionBusInterface;

final class InMemoryProjectionBus implements ProjectionBusInterface
{
    /** @var Projection[] */
    private array $projections = [];
    /** @var array<string, int>  */
    private array $projectionsCounter = [];

    public function project(Projection $projection): void
    {
        $this->projections[$projection::class] = $projection;
        array_key_exists($projection::class, $this->projectionsCounter) ? $this->projectionsCounter[$projection::class]++ : $this->projectionsCounter[$projection::class] = 1;
    }
    public function getProjections(): array
    {
        return $this->projections;
    }

    public function getProjectionsCounter(): array
    {
        return $this->projectionsCounter;
    }
}
