<?php

declare(strict_types=1);

namespace App\Twit\Application;

interface QueryBusInterface
{
    public function query(Query $query): mixed;
}
