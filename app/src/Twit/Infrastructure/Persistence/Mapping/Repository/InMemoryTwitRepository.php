<?php

namespace App\Twit\Infrastructure\Persistence\Mapping\Repository;

use App\Twit\Domain\Entity\Twit;
use App\Twit\Domain\Exception\TwitNotFoundException;
use App\Twit\Domain\Repository\TwitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InMemoryTwitRepository implements TwitRepository
{
    /** @var Collection<int, Twit> $twits */
    private Collection $twits;

    public function __construct()
    {
        $this->twits = new ArrayCollection();
    }

    /**
     * @param string $uuid
     * @return Twit
     * @throws TwitNotFoundException
     */
    public function findOneByUuidOrFail(string $uuid): Twit
    {
        return $this->findOneByUuid($uuid) ?? throw new TwitNotFoundException('Todo switch to different exception - Twit ID not found');
    }

    public function findOneByUuid(string $uuid): ?Twit
    {
        foreach ($this->twits as $twit) {
            if ($twit->uuid() === $uuid) {
                return $twit;
            }
        }
        return null;
    }

    public function save(Twit $twit): void
    {
        $this->twits->add($twit);
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        //return $this->twits->key($criteria['id']);
    }
}
