<?php

namespace App\Controller;

use App\Message\SendTwitMessage;

use App\Twit\Domain\Repository\TwitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Routing\Annotation\Route;

class PruebaController extends AbstractController
{
    #[Route('/prueba', name: 'app_prueba')]
    public function index(TwitRepository $repository, MessageBusInterface $bus): Response
    {
        $twits = $repository->findBy([], null, 50);

        foreach (range(1, 1) as $i){
            $bus->dispatch(new SendTwitMessage('hola manolito ' . random_int(0, 1337) . ' - ' . $i), [
                new DelayStamp(1000)
            ]);
        }


        return $this->render('prueba/index.html.twig', [
            'controller_name' => 'PruebaController',
            'twits'           => $twits
        ]);
    }
}
