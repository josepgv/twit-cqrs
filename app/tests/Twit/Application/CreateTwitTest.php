<?php

namespace App\Tests\Twit;


use App\Twit\Application\CreateTwit\CreateTwitService;
use App\Twit\Application\CreateTwit\DTO\CreateTwitInputDTO;
use App\Twit\Domain\Entity\Twit;
use App\Twit\Domain\Repository\TwitRepository;
use App\Twit\Infrastructure\Persistence\Mapping\Repository\InMemoryTwitRepository;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CreateTwitTest extends TestCase
{
    public function testCreateTwit(): void
    {
        $repository = new InMemoryTwitRepository();
        $createTwitService = new CreateTwitService($repository);

        $date = new \DateTime('now');
        $twitDTO = CreateTwitInputDTO::create(1337, 'This is the content', $date);

        $twitUuid = $createTwitService->handle($twitDTO);

        $this->assertTrue(Uuid::isValid($twitUuid));
    }

    public function testCreateTwitWithMockRepository(): void
    {
        $date = new \DateTime('now');
        $twitDTO = CreateTwitInputDTO::create(1337, 'This is the content', $date);


        $repository = $this->createMock(TwitRepository::class);
        $service = new CreateTwitService($repository);


        $repository->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (Twit $twit) use ($date): bool {
                    return $twit->content() === 'This is the content'
                        && $twit->user_id() === 1337
                        && $twit->getCreatedAt() === $date
                        && Uuid::isValid($twit->uuid());
                })
            );

        $twitUuid = $service->handle($twitDTO);

        $this->assertTrue(Uuid::isValid($twitUuid));
    }

}
