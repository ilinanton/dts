<?php

declare(strict_types=1);

namespace App\Presentation\Cli;

use App\Application\SyncOutputInterface;

final class StdoutSyncOutput implements SyncOutputInterface
{
    public function write(string $message): void
    {
        echo $message;
    }

    public function writeLine(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
