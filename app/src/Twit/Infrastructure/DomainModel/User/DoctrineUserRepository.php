<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\DomainModel\User;

use App\Twit\Domain\User\User;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Domain\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    public function ofId(UserId $userId): ?User
    {
        return $this->entityManager->getRepository(User::class)
            ->findOneBy(
                [
                    'userId' => $userId->id()
                ]
            );
    }

    public function ofNickName(UserNickName $nickName): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy([
                'nickName' => $nickName->nickName()
            ]);
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
        //$this->entityManager->flush();
    }
}
