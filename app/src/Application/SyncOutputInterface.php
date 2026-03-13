<?php

declare(strict_types=1);

namespace App\Application;

interface SyncOutputInterface
{
    public function write(string $message): void;

    public function writeLine(string $message): void;
}
