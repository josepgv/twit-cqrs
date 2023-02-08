<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\TwitType;
use App\Twit\Application\CommandBusInterface;
use App\Twit\Application\QueryBusInterface;
use App\Twit\Application\Twit\Command\ComposeTwitCommand;
use App\Twit\Application\Twit\Query\AnonymouseTimelineQuery;
use App\Twit\Application\Twit\Query\TimelineTwitResponse;
use App\Twit\Application\User\Command\SignUpCommand;
use App\Twit\Application\User\Query\TotalUsersCountQuery;
use App\Twit\Domain\Twit\TwitId;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserRepositoryInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PruebaController extends AbstractController
{
    #[Route('/prueba', name: 'app_prueba')]
    public function index(
        Request $request,
        UserRepositoryInterface $repository,
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
    ): Response {
        $form = $this->createForm(TwitType::class);
        $faker = Factory::create('es_ES');
        $totalUsers = $queryBus->query(new TotalUsersCountQuery());

        /** @var array<TimelineTwitResponse> $anonTimeline */
        $anonTimeline = $queryBus->query(new AnonymouseTimelineQuery());

        $allUsers = $repository->getAll(15); // @todo: must create a Read Model Query

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $twitType = $form->getData();

            $command = new SignUpCommand(
                UserId::nextIdentity()->id(),
                $faker->userName(),
                $faker->email(),
                $faker->realTextBetween(15, 50),
                $faker->url()
            );
            $commandBus->handle($command);

            $user = $repository->ofId(UserId::fromString($command->userId));

            $compTwit = new ComposeTwitCommand(TwitId::nextIdentity()->id(), $user, $twitType['content']);
            $commandBus->handle($compTwit);

            return $this->redirectToRoute('app_prueba');
        }



        return $this->render('prueba/index.html.twig', [
            'controller_name' => 'PruebaController',
            'user' => $user ?? null,
            'totalUsers' => $totalUsers,
            'users' => $allUsers,
            'anonTimeline' => $anonTimeline,
            'form' => $form,
        ]);
    }

    #[Route('/users/totalcount', name: 'total_users_count')]
    public function totalUsersCount(QueryBusInterface $bus): JsonResponse
    {
        return new JsonResponse($bus->query(new TotalUsersCountQuery()));
    }
}
