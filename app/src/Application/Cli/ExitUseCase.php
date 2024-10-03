<?php

namespace App\Application\Cli;

use App\Application\UseCaseInterface;

final readonly class ExitUseCase implements UseCaseInterface
{
    public function execute(): void
    {
        throw new \Exception('Goodbye!', 0);
    }
}
