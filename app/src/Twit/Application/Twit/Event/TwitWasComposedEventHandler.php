<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Event;

use App\Twit\Domain\Twit\Event\TwitWasComposed;
use Psr\Log\LoggerInterface;

class TwitWasComposedEventHandler
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(TwitWasComposed $event): void
    {
        $this->logger->alert("Twit was composed!!", (array)$event);
        // todo: project into author's followers' timelines
    }
}
