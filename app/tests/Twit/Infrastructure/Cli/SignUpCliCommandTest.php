<?php

declare(strict_types=1);

namespace App\Tests\Twit\Infrastructure\Cli;

use App\Command\SignUpCliCommand;
use App\Twit\Application\CommandBusInterface;
use App\Twit\Infrastructure\Application\SymfonyCommandBus;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\Envelope;

class SignUpCliCommandTest extends KernelTestCase
{
    public function testSignUpCliCommandCanSignUpCorrectlyAndTheUserNicknameIsShown(): void
    {
        $kernel      = self::bootKernel();
        $application = new Application($kernel);

        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus->expects($this->atLeastOnce())
            ->method('handle')
            ->willReturn(new Envelope(new \stdClass()));

        $command       = new SignUpCliCommand($commandBus);
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['Manolito', 'mano@lit.com', 'puf', 'https://google.com']);

        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('User "Manolito" created', $output);
    }
}
