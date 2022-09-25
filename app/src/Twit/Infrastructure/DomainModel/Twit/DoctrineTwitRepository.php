<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\DomainModel\Twit;

use App\Twit\Domain\Twit\Twit;
use App\Twit\Domain\Twit\TwitId;
use App\Twit\Domain\User\UserId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineTwitRepository implements \App\Twit\Domain\Twit\TwitRepositoryInterface
{
    private readonly EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Twit::class);
    }

    public function ofId(TwitId $twitId): ?Twit
    {
        return $this->repository->findOneBy(['twitId' => $twitId->id()]);
    }

    /**
     * @return ArrayCollection<int, Twit>
     */
    public function ofUserId(UserId $userId): ArrayCollection
    {
        /** @var Twit[] $results */
        $results = $this->repository->findBy(['userId' => $userId->id()]);

        return new ArrayCollection($results);
    }

    public function add(Twit $twit): void
    {
        $this->entityManager->persist($twit);
    }
}
