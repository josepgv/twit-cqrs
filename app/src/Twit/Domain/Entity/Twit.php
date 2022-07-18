<?php

declare(strict_types=1);

namespace App\Twit\Domain\Entity;

use DateTime;

class Twit
{
    private int $id;

    public function __construct(private string $uuid, private int $user_id, private string $content, private DateTime $created_at)
    {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function user_id(): int
    {
        return $this->user_id;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }
}
