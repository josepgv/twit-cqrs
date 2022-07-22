<?php

namespace App\Tests\Twit\Infrastructure;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class TotalCountTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = self::createClient();
        $client->setServerParameter('CONTENT_TYPE', 'application/json');
        $client->request('GET', '/users/totalcount');
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('amount', $response, 'mensajito');
        $date = new \DateTimeImmutable($response['last_updated']['date'], new \DateTimeZone($response['last_updated']['timezone']));
        $this->assertInstanceOf(\DateTimeImmutable::class, $date);

        $this->assertResponseIsSuccessful();
    }
}
