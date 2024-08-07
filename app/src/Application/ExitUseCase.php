<?php

namespace App\Application;

final readonly class ExitUseCase implements UseCaseInterface
{
    public function execute(): void
    {
        throw new \Exception('Goodbye!', 0);
    }
}
