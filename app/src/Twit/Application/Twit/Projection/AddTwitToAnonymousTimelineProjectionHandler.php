<?php

declare(strict_types=1);

namespace App\Twit\Application\Twit\Projection;

class AddTwitToAnonymousTimelineProjectionHandler
{
    public const ANON_TIMELINE_PREFIX = 'anon_timeline';

    public function __construct(private readonly \Redis $redis)
    {
    }

    public function __invoke(AddTwitToAnonymousTimelineProjection $projection): void
    {
        $this->redis->lPush(AddTwitToAnonymousTimelineProjectionHandler::ANON_TIMELINE_PREFIX, serialize([
            'id' => $projection->getTwit()->id()->id(),
            'username' => $projection->getTwit()->user()->nickName()->nickName(),
            'userid' => $projection->getTwit()->user()->userId()->id(),
            'content' => $projection->getTwit()->content()->getContent(),
            'date' => $projection->getTwit()->date()->format('d-m-Y H:i:s'),
        ]));
    }
}
