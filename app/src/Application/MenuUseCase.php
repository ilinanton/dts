<?php

namespace App\Application;

class MenuUseCase implements UseCaseInterface
{
    public function execute(): void
    {
        echo 'Command list:' . PHP_EOL;

        $commands = Command::cases();
        foreach ($commands as $key => $command) {
            echo '[' . $key . '] ' . $command->name . ' - ' . $command->value . PHP_EOL;
        }
    }
}
