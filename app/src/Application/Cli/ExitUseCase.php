<?php

declare(strict_types=1);

namespace App\Application\Cli;

use App\Application\UseCaseInterface;
use Exception;

final readonly class ExitUseCase implements UseCaseInterface
{
    public function execute(): void
    {
        throw new Exception('Goodbye!', 0);
    }
}
