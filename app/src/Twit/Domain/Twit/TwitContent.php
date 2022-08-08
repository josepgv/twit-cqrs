<?php

declare(strict_types=1);

namespace App\Twit\Domain\Twit;

use Stringable;

final class TwitContent implements Stringable
{
    protected const MAX_TWIT_LENGTH = 255;

    private function __construct(private readonly string $content)
    {
    }

    public static function fromString(string $content): TwitContent
    {
        if (strlen($content) > 255) {
            throw new TwitIsTooLongException(
                sprintf("Twit has %d characters, max is %d", strlen($content), self::MAX_TWIT_LENGTH)
            );
        }
        return new TwitContent($content);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function __toString(): string
    {
        return $this->getContent();
    }
}
