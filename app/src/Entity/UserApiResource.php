<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Ramsey\Uuid\UuidInterface;

#[ApiResource(shortName: 'user')]
final class UserApiResource
{
    #[ApiProperty(identifier: true)]
    public ?UuidInterface $id = null;

    public string $userId;
    public string $nickName;
    public string $email;
    public ?string $bio;
    public ?string $website;

}
