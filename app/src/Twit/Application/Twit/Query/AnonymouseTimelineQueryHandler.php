<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Query;

use App\Twit\Application\Query;
use App\Twit\Application\Twit\Projection\AddTwitToAnonymousTimelineProjectionHandler;

class AnonymouseTimelineQueryHandler implements Query
{
    public function __construct(private readonly \Redis $redis)
    {
    }

    public function __invoke(AnonymouseTimelineQuery $query): array
    {
        $twits = $this->redis->lRange(AddTwitToAnonymousTimelineProjectionHandler::ANON_TIMELINE_PREFIX, 0, 5);
        $arrayTwits = [];
        foreach ($twits as $twit) {
            $twitData = unserialize($twit);
            $arrayTwits[] = new TimelineTwitResponse(
                $twitData['id'],
                $twitData['userid'],
                $twitData['username'],
                $twitData['content'],
                \DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $twitData['date'])
            );
        }

        return $arrayTwits;
    }
}
