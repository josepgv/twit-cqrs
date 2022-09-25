<?php

declare(strict_types=1);

namespace App\Controller;

use App\Twit\Application\CommandBusInterface;
use App\Twit\Application\QueryBusInterface;
use App\Twit\Application\Twit\Command\ComposeTwitCommand;
use App\Twit\Application\Twit\Query\AnonymouseTimelineQuery;
use App\Twit\Application\Twit\Query\TimelineTwitResponse;
use App\Twit\Application\User\Command\SignUpCommand;
use App\Twit\Application\User\Query\TotalUsersCountQuery;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserRepositoryInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PruebaController extends AbstractController
{
    #[Route('/prueba', name: 'app_prueba')]
    public function index(
        UserRepositoryInterface $repository,
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
    ): Response {
        // $twits = $twitRepository->ofUserId(UserId::fromString('84296aeb-e9f0-4927-a4db-dec2af82f3cc'));
        // var_dump($twits);
        // var_dump($twits[0]->user()->bio());
        $faker = Factory::create('es_ES');
        $totalUsers = $queryBus->query(new TotalUsersCountQuery());

        /** @var array<TimelineTwitResponse> $anonTimeline */
        $anonTimeline = $queryBus->query(new AnonymouseTimelineQuery());

        $allUsers = $repository->getAll(15); // @todo: must create a Read Model Query

        $command = new SignUpCommand(
            UserId::nextIdentity()->id(),
            $faker->userName(),
            $faker->email(),
            $faker->realTextBetween(15, 50),
            $faker->url()
        );
        $commandBus->handle($command);

        // $user = $repository->ofId(UserId::fromString('6b109464-7a9d-4972-aefb-74885bd97b18'));
        $user = $repository->ofId(UserId::fromString($command->userId));
        /*$user = [
            'userId' => $user?->userId()->id(),
            'nickName' => $user?->nickName()->nickName(),
            'bio' => $user?->bio(),
            'website' => $user?->website()?->uri(),
            'email' => $user?->email()->email(),
        ];*/
        // return new JsonResponse(['user' => $user]);

//        $compTwit = new ComposeTwitCommand(UserId::nextIdentity()->id(), $user->userId()->id(), $faker->realTextBetween(50, 240));
        $compTwit = new ComposeTwitCommand(UserId::nextIdentity()->id(), $user, $faker->realTextBetween(50, 240));
        $commandBus->handle($compTwit);

        return $this->render('prueba/index.html.twig', [
            'controller_name' => 'PruebaController',
            'user' => $user,
            'totalUsers' => $totalUsers,
            'users' => $allUsers,
            'anonTimeline' => $anonTimeline,
        ]);
    }

    #[Route('/users/totalcount', name: 'total_users_count')]
    public function totalUsersCount(QueryBusInterface $bus): JsonResponse
    {
        return new JsonResponse($bus->query(new TotalUsersCountQuery()));
    }
}
