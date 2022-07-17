<?php

namespace App\Twit\Infrastructure\Persistence\Mapping\Repository;

use App\Twit\Domain\Entity\Twit;
use App\Twit\Domain\Exception\TwitNotFoundException;
use App\Twit\Domain\Repository\TwitRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Exception;

class DoctrineTwitRepository implements TwitRepository
{



    private ObjectManager $entityManager;
    private ServiceEntityRepository $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->repository = new ServiceEntityRepository($managerRegistry, Twit::class);
        $this->entityManager = $managerRegistry->getManager();
    }

    /**
     * @param string $uuid
     * @return Twit
     * @throws TwitNotFoundException
     */
    public function findOneByUuidOrFail(string $uuid): Twit
    {
        $twit = $this->findOneByUuid($uuid);
        if(!$twit) {
            throw new TwitNotFoundException("TODO FIX THIS EXCEPTION - Twit UUID {$uuid} not found");
        }
        return $twit;
    }

    public function findOneByUuid(string $uuid): ?Twit
    {
        return $this->repository->findOneBy(['uuid' => $uuid]);
    }

    public function save(Twit $twit): void
    {
        $this->entityManager->persist($twit);
        $this->entityManager->flush();
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }
}
