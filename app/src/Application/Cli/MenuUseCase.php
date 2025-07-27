<?php

declare(strict_types=1);

namespace App\Application\Cli;

use App\Application\UseCaseInterface;
use App\Presentation\Cli\Command;

final readonly class MenuUseCase implements UseCaseInterface
{
    public function execute(): void
    {
        echo 'Command list:' . PHP_EOL;

        $commands = Command::cases();
        $grouped = [];

        foreach ($commands as $index => $command) {
            $grouped[$command->category()][$index] = $command;
        }

        foreach (Command::CATEGORY as $category) {
            if (empty($grouped[$category])) {
                continue;
            }

            echo '- ' . $category . ':' . PHP_EOL;
            ksort($grouped[$category]);

            foreach ($grouped[$category] as $index => $command) {
                echo '  [' . $index . '] - ' . $command->value . PHP_EOL;
            }
        }
    }
}
