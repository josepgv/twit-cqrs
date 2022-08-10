<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Command;

use App\Twit\Application\EventBusInterface;
use App\Twit\Domain\Twit\Twit;
use App\Twit\Domain\Twit\TwitAlreadyExistsException;
use App\Twit\Domain\Twit\TwitContent;
use App\Twit\Domain\Twit\TwitId;
use App\Twit\Domain\Twit\TwitRepositoryInterface;
use App\Twit\Domain\Twit\UserCantTwitException;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserRepositoryInterface;

class ComposeTwitCommandHandler
{
    public function __construct(
        private readonly TwitRepositoryInterface $twitRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus
    ) {
    }

    public function __invoke(ComposeTwitCommand $command): void
    {
        $twit = Twit::compose(
            TwitId::fromString($command->twitId),
            UserId::fromString($command->userId),
            TwitContent::fromString($command->content)
        );

        $this->checkTwitWithSameIdDoesNotExist($twit->id());

        $this->checkUserCanTwit($twit->userId());

        $this->twitRepository->add($twit);

        $this->eventBus->notifyAll($twit->getAndFlushDomainEvents());
    }

    private function checkTwitWithSameIdDoesNotExist(TwitId $twitId): void
    {
        $existingTwit = $this->twitRepository->ofId($twitId);
        if (null !== $existingTwit) {
            throw TwitAlreadyExistsException::withIdOf($existingTwit->id());
        }
    }

    private function checkUserCanTwit(UserId $userId): void
    {
        // 1st check the user exists
        $user = $this->userRepository->ofId($userId);
        if (null !== $user) {
            throw UserCantTwitException::withIdOf($userId, "the user does not exist");
        }

        // Then check other possible reasons, such as user is banned, throttled or any other reason
    }
}
