<?php

namespace App\Tests\Twit;


use App\Twit\Domain\Entity\Twit;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class TwitCreateTest extends TestCase
{
    public function testTwitCreation(): void
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2022-06-07 19:29:00');
        $uuid = Uuid::v4()->toRfc4122();
        $twit = new Twit($uuid, 50, 'This is the content', $date);

        $this->assertEquals('This is the content', $twit->content());
        //$this->assertEquals(1337, $twit->getUserId());
        //$this->assertEquals($date, $twit->getCreatedAt());
        $this->assertEquals($uuid, $twit->uuid());

    }


}
