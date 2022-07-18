<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SendTwitMessage;
use App\Twit\Application\CreateTwit\CreateTwitService;
use App\Twit\Application\CreateTwit\DTO\CreateTwitInputDTO;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsMessageHandler]
final class SendTwitMessageHandler implements MessageHandlerInterface
{
    public function __construct(private readonly CreateTwitService $createTwit)
    {
    }

    public function __invoke(SendTwitMessage $message)
    {
        $twitDto = CreateTwitInputDTO::create(random_int(0, 100), $message->getTwitContent(), null);
        $this->createTwit->handle($twitDto);
    }
}
