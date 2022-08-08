<?php

declare(strict_types=1);

namespace App\Twit\Domain\Twit;

use App\Twit\Domain\DomainEvent;
use App\Twit\Domain\Twit\Event\TwitWasComposed;
use App\Twit\Domain\User\UserId;

class Twit
{
    /** @var DomainEvent[] */
    private array $domainEvents = [];

    private function __construct(
        protected string $twitId,
        protected string $userId,
        protected string $content,
        protected \DateTimeImmutable $date
    ) {
        $this->addDomainEvent(TwitWasComposed::fromTwit($this));
    }

    public static function compose(
        TwitId $twitId,
        UserId $userId,
        TwitContent $content
    ): Twit {
        return new Twit($twitId->id(), $userId->id(), $content->getContent(), new \DateTimeImmutable('now'));
    }

    public function id(): TwitId
    {
        return TwitId::fromString($this->twitId);
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->userId);
    }

    public function content(): TwitContent
    {
        return TwitContent::fromString($this->content);
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }

    protected function addDomainEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    /**
     * @return DomainEvent[]
     */
    public function getAndFlushDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
