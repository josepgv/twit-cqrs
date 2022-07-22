<?php

namespace App\Tests\Twit\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class TotalCountTest extends WebTestCase
{
    protected static ?AbstractBrowser $client = null;

    public function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameter('CONTENT_TYPE', 'application/json');
        }
    }

    public function testSomething(): void
    {
        $crawler = self::$client->request('GET', '/users/totalcount');
        $responseContent = json_decode(self::$client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('amount', $responseContent, 'mensajito');
        $date = new \DateTimeImmutable($responseContent['last_updated']['date'], new \DateTimeZone($responseContent['last_updated']['timezone']));
        $this->assertInstanceOf(\DateTimeImmutable::class, $date);

        $this->assertResponseIsSuccessful();
    }
}
