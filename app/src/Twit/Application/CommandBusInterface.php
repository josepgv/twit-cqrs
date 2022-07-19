<?php

declare(strict_types=1);

namespace App\Twit\Application;

use Symfony\Component\Messenger\Envelope;

interface CommandBusInterface
{
    public function handle(object $command): Envelope;
}
