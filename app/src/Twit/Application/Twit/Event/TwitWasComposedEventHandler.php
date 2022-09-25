<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Event;

use App\Twit\Application\ProjectionBusInterface;
use App\Twit\Application\Twit\Projection\AddTwitToAnonymousTimelineProjection;
use App\Twit\Domain\Twit\Event\TwitWasComposed;
use App\Twit\Domain\Twit\TwitId;
use App\Twit\Domain\Twit\TwitRepositoryInterface;
use Psr\Log\LoggerInterface;

class TwitWasComposedEventHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TwitRepositoryInterface $twitRepository,
        private readonly ProjectionBusInterface $projectionBus
    ) {
    }

    public function __invoke(TwitWasComposed $event): void
    {
        $this->logger->alert('Twit was composed!!', (array) $event);
        // todo: project into author's followers' timelines
        // Project into general timeline
        $twit = $this->twitRepository->ofId(TwitId::fromString($event->twitId()));
        $this->projectionBus->project(new AddTwitToAnonymousTimelineProjection($twit));
    }
}
