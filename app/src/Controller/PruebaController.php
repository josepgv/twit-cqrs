<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\SendTwitMessage;
use App\Twit\Application\CommandBusInterface;
use App\Twit\Application\User\Command\SignUpCommand;
use App\Twit\Domain\Repository\TwitRepository;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PruebaController extends AbstractController
{
    #[Route('/prueba', name: 'app_prueba')]
    public function index(UserRepositoryInterface $repository, CommandBusInterface $commandBus): Response
    {
        //$handler = new SignUpCommandHandler($repository);
        //$handler(new SignUpCommand(
        //    UserId::nextIdentity()->id(),
        //    'manolito' . random_int(0, 5000),
        //    'mano@lito.com'
        //));
        $command = new SignUpCommand(
            UserId::nextIdentity()->id(),
            'manolito' . random_int(0, 5000),
            'mano@lito.com'
        );
        $commandBus->handle($command);

        //$user = $repository->ofId(UserId::fromString('6b109464-7a9d-4972-aefb-74885bd97b18'));
        $user = $repository->ofId(UserId::fromString($command->userId));
        $user = [
            'userId' => $user->userId()->id(),
            'nickName' => $user->nickName()->nickName(),
            'bio' => $user->bio(),
            'website' => $user->website()?->uri(),
            'email' => $user->email()->email(),
        ];
        //return new JsonResponse(['user' => $user]);

        return $this->render('prueba/index.html.twig', [
            'controller_name' => 'PruebaController',
            'user' => $user,
        ]);
    }
}
