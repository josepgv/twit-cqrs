<?php

namespace App\Command;

use App\Twit\Application\CommandBusInterface;
use App\Twit\Application\User\Command\SignUpCommand;
use App\Twit\Domain\User\UserEmail;
use App\Twit\Domain\User\UserId;
use App\Twit\Domain\User\UserNickName;
use App\Twit\Domain\User\UserWebsite;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'twit:sign-up',
    description: 'Add a short description for your command',
)]
class SignUpCliCommand extends Command
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
        parent::__construct('SignUpCliCommand');
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userNickName = UserNickName::pick($io->ask('What is the user name?'));
        $userEmail = UserEmail::fromString($io->ask('What is the user email?'));
        $userBio = $io->ask('What is the user bio? (optional)');
        $userWebsite = $io->ask('What is the user website? (optional)');
        if($userWebsite){
            $userWebsite = UserWebsite::fromString($userWebsite);
        }

        $signUpCommand = new SignUpCommand(
            UserId::nextIdentity(),
            $userNickName,
            $userEmail,
            $userBio,
            $userWebsite);

        $this->commandBus->handle($signUpCommand);

        $io->success(sprintf('User "%s" created successfully!', $signUpCommand->nickName));

        return Command::SUCCESS;
    }
}
