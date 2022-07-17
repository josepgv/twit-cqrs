<?php

namespace App\Message;

final class SendTwitMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

    public function __construct(private string $twitContent)
    {

    }

    public function getTwitContent(): string
    {
        return $this->twitContent;
    }
}
