<?php

declare(strict_types=1);

namespace App\Twit\Domain\Repository;

use App\Twit\Domain\Entity\Twit;

interface TwitRepository
{
    public function findOneByUuidOrFail(string $uuid): Twit;

    public function findOneByUuid(string $uuid): ?Twit;

    public function save(Twit $twit): void;

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array;
}
