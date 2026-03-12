<?php

declare(strict_types=1);

namespace App\Application\Cli;

final readonly class MenuItem
{
    public function __construct(
        public int $index,
        public string $name,
        public string $category,
    ) {
    }
}
