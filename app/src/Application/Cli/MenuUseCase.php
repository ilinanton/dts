<?php

declare(strict_types=1);

namespace App\Application\Cli;

use App\Application\UseCaseInterface;

final readonly class MenuUseCase implements UseCaseInterface
{
    /** @param array<string> $categories */
    public function __construct(
        private MenuItemCollection $menuItems,
        private array $categories,
    ) {
    }

    public function execute(): void
    {
        echo 'Command list:' . PHP_EOL;

        $grouped = [];

        foreach ($this->menuItems as $item) {
            $grouped[$item->category][$item->index] = $item;
        }

        foreach ($this->categories as $category) {
            if (empty($grouped[$category])) {
                continue;
            }

            echo '- ' . $category . ':' . PHP_EOL;
            ksort($grouped[$category]);

            foreach ($grouped[$category] as $index => $item) {
                echo '  [' . $index . '] - ' . $item->name . PHP_EOL;
            }
        }
    }
}
