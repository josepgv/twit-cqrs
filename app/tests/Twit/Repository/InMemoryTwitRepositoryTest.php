<?php

namespace App\Tests\Twit\Repository;

use App\Twit\Domain\Entity\Twit;
use App\Twit\Infrastructure\Persistence\Mapping\Repository\InMemoryTwitRepository;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class InMemoryTwitRepositoryTest extends TestCase
{
    public function testInMemoryRepository(): void
    {
        $repo = new InMemoryTwitRepository();

        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2022-06-07 19:29:00');
        $uuid = Uuid::v4()->toRfc4122();
        $twit = new Twit($uuid, 500, 'This is the content', $date );

        $repo->save($twit);

        $this->assertEquals($twit, $repo->findOneByUuid($uuid));

        $this->expectException(\Exception::class);
        $repo->findOneByUuidOrFail(50000000);

    }
}
