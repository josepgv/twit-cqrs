<?php

declare(strict_types=1);

namespace App\ApiDataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\UserApiResource;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserApiResourcessItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return UserApiResource::class === $resourceClass;
    }

    /**
     * @param UuidInterface $id
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?UserApiResource
    {
        $user = $this->userRepository->ofId(UserId::fromString($id->toString()));
        if ($user) {
            $userApiResource = new UserApiResource();
            $userApiResource->id = Uuid::fromString($user->userId()->id());
            $userApiResource->userId = $user->userId()->id();
            $userApiResource->email = $user->email()->email();
            $userApiResource->bio = $user->bio();
            $userApiResource->website = $user->website()->uri();

            return $userApiResource;
        }

        return null;
    }
}
