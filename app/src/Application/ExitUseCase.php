<?php

namespace App\Application;

class ExitUseCase implements UseCaseInterface
{
    public function execute(): void
    {
        throw new \Exception('Goodbye!', 0);
    }
}
