<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\UserApiResource;
use App\Twit\Application\CommandBusInterface;
use App\Twit\Application\User\Command\SignUpCommand;
use App\Twit\Domain\Common\UuidIdentity;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Domain\User\UserWebsite;

class UserApiResourceDataPersister implements DataPersisterInterface
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
        return $data instanceof UserApiResource;// && null === $data->id;
    }

    /**
     * @inheritDoc
     */
    public function persist($data): UserApiResource
    {
        $command = new SignUpCommand(
            UserId::nextIdentity()->id(),
            UserNickName::pick($data->nickName)->nickName(),
            UserEmail::fromString($data->email)->email(),
            $data->bio,
            UserWebsite::fromString($data->website)->uri()
        );
        $this->commandBus->handle($command);

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function remove($data)
    {
        // TODO: Implement remove() method.
    }
}
