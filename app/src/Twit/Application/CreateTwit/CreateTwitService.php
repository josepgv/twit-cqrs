<?php

namespace App\Twit\Application\CreateTwit;

use App\Twit\Application\CreateTwit\DTO\CreateTwitInputDTO;
use App\Twit\Domain\Entity\Twit;
use App\Twit\Domain\Repository\TwitRepository;

class CreateTwitService
{
    public function __construct(private readonly TwitRepository $repository)
    {

    }

    public function handle(CreateTwitInputDTO $inputDTO): string
    {
        $twit = new Twit(\Symfony\Component\Uid\Uuid::v4()->toRfc4122(), $inputDTO->user_id, $inputDTO->content, $inputDTO->created_at);

        $this->repository->save($twit);

        return $twit->uuid();
    }
}
