<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\SendTwitMessage;
use App\Twit\Application\User\SignUpCommand;
use App\Twit\Application\User\SignUpCommandHandler;
use App\Twit\Domain\Repository\TwitRepository;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Routing\Annotation\Route;

class PruebaController extends AbstractController
{
    #[Route('/prueba', name: 'app_prueba')]
    public function index(UserRepositoryInterface $repository, MessageBusInterface $bus): Response
    {
        //$handler = new SignUpCommandHandler($repository);
        //$handler(new SignUpCommand(
        //    UserId::nextIdentity()->id(),
        //    'manolito',
        //    'mano@lito.com'
        //));

        $user = $repository->ofId(UserId::fromString('6b109464-7a9d-4972-aefb-74885bd97b18'));
        $user = [
            'userId' => $user->userId()->id(),
            'nickName' => $user->nickName()->nickName(),
            'bio' => $user->bio(),
            'website' => $user->website()?->uri(),
            'email' => $user->email()->email(),
        ];
        return new JsonResponse(['user' => $user]);

    }
}
