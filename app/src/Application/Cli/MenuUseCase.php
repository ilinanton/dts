<?php

namespace App\Application\Cli;

use App\Application\UseCaseInterface;
use App\Presentation\Cli\Command;

final readonly class MenuUseCase implements UseCaseInterface
{
    public function execute(): void
    {
        echo 'Command list:' . PHP_EOL;

        $commands = Command::cases();
        foreach ($commands as $key => $command) {
            echo '[' . $key . '] - ' . $command->value . PHP_EOL;
        }
    }
}
