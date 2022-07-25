<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Event;

use App\Twit\Application\ProjectionBusInterface;
use App\Twit\Application\SendUserWelcomeInterface;
use App\Twit\Application\User\Projection\CreateUserFollowersCountProjection;
use App\Twit\Application\User\Projection\IncrementTotalUsersProjection;
use App\Twit\Domain\User\Event\UserSignedUp;
use App\Twit\Domain\User\UserId;
use Psr\Log\LoggerInterface;

class UserSignedUpEventHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ProjectionBusInterface $projectionBus,
        private readonly SendUserWelcomeInterface $sendUserWelcome
    ) {
    }

    public function __invoke(UserSignedUp $event): void
    {
        $this->logger->info(sprintf('User %s signed up on %s', $event->userId(), $event->occurredOn()->format('d/m/Y H:i:s')));
        $this->projectionBus->project(new IncrementTotalUsersProjection());
        $this->projectionBus->project(new CreateUserFollowersCountProjection(UserId::fromString($event->userId())));
        $this->sendUserWelcome->send(UserId::fromString($event->userId()));
    }
}
