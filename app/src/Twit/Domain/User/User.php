<?php

declare(strict_types=1);

namespace App\Twit\Domain\User;

class User
{
    public function __construct(
        protected string $userId,
        protected string $nickName,
        protected string $email,
        protected ?string $bio = null,
        protected ?string $website = null,
    ) {
        $this->setBio($bio);
    }

    public static function signUp(
        UserId $userId,
        UserNickName $nickName,
        UserEmail $email,
        ?string $bio = null,
        ?UserWebsite $website = null,
    ): static {
        return new static(
            $userId->id(),
            $nickName->nickName(),
            $email->email(),
            $bio,
            $website?->uri()
        );
    }

    private function setBio(?string $bio): void
    {
        $this->bio = $this->checkIsNotNull($bio, 'Biography cannot be empty');
    }

    private function checkIsNotNull(?string $value, string $errorMessage): string
    {
        if ('' === $value) {
            throw new \InvalidArgumentException($errorMessage);
        }
        return $value;
    }

    public function userId(): UserId
    {
        return UserId::fromString($this->userId);
    }

    public function nickName(): UserNickName
    {
        return UserNickName::pick($this->nickName);
    }

    public function email(): UserEmail
    {
        return UserEmail::fromString($this->email);
    }

    public function bio(): ?string
    {
        return $this->bio;
    }

    public function website(): ?UserWebsite
    {
        return ($this->website !== null) ? UserWebsite::fromString($this->website) : null;
    }
}
