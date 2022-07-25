<?php

declare(strict_types=1);

namespace App\Twit\Infrastructure\Application;

use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class SwiftmailerSendUserWelcome implements \App\Twit\Application\SendUserWelcomeInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(UserId $userId): void
    {
        $user = $this->userRepository->ofId($userId);

        $email = (new TemplatedEmail())
            ->from(new Address('manolito@twitter-cqrs.com', 'Manolito de Twit CQRS'))
            ->to($user->email()->email())
            ->replyTo('noreply@twitter-cqrs.com')
            ->subject('Welcome to Twit CQRS!')
            ->htmlTemplate('Infrastructure/DomainModel/User/Emails/welcome.html.twig')
            ->context(
                [
                    'nickname' => $user->nickName()->nickName(),
                ]
            );
        $this->logger->alert('Trying to send email');
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Email could not be sent', [$e->getMessage()]);
        }
    }
}
