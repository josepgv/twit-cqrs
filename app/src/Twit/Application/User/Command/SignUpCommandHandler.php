<?php

declare(strict_types=1);

namespace App\Twit\Application\User\Command;

use App\Twit\Application\EventBusInterface;
use App\Twit\Domain\User\Event\UserSignedUp;
use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserAlreadyExistsException;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Domain\User\UserRepositoryInterface;
use App\Twit\Domain\User\UserWebsite;

class SignUpCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventBusInterface $eventBus
    ) {
    }

    public function __invoke(SignUpCommand $command): void
    {
        $userId = UserId::fromString($command->userId);
        $userNickName = UserNickName::pick($command->nickName);
        $email = UserEmail::fromString($command->email);
        $bio = $command->bio;
        $website = (null !== $command->website) ? UserWebsite::fromString($command->website) : null;

        $existingUser = $this->userRepository->ofId($userId);
        $this->checkUserWithSameIdDoesNotExist($existingUser, $userId);

        $existingUser = $this->userRepository->ofNickName($userNickName);
        $this->checkUserWithSameNickNameDoesNotExist($existingUser, $userNickName);

        $user = User::signUp(
            $userId,
            $userNickName,
            $email,
            $bio,
            $website
        );

        $this->userRepository->add($user);
        // $this->eventBus->notify(UserSignedUp::fromUser($user));
        $this->eventBus->notifyAll($user->getAndFlushDomainEvents());
    }

    private function checkUserWithSameIdDoesNotExist(?User $existingUser, UserId $userId): void
    {
        if (null !== $existingUser) {
            throw UserAlreadyExistsException::withIdOf($userId);
        }
    }

    private function checkUserWithSameNickNameDoesNotExist(?User $existingUser, UserNickName $userNickName): void
    {
        if (null !== $existingUser) {
            throw UserAlreadyExistsException::withUserNickNameOf($userNickName);
        }
    }
}
