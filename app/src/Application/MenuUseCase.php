<?php

namespace App\Application;

final class MenuUseCase implements UseCaseInterface
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
