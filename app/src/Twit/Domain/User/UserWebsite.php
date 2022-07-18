<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

use App\Twit\Domain\Common\ValueObject;
use Assert\Assertion;
use Assert\AssertionFailedException;

class UserWebsite extends ValueObject implements \Stringable
{

    public function __construct(
        private string $uri
    ) {
        $this->setUri($uri);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function setUri(string $uri): void
    {
        $this->assertValidUrl($uri);

        $this->uri = $uri;
    }

    private function assertValidUrl(string $uri): void
    {
        try {
            Assertion::url($uri);
        } catch (AssertionFailedException $assertionFailedException) {
            throw new \InvalidArgumentException(sprintf("%s is not a valid URL", $uri));
        }
    }

    public function __toString(): string
    {
        return $this->uri;
    }

    public function uri(): string
    {
        return $this->uri;
    }
}
