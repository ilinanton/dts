<?php

declare(strict_types=1);

namespace App\Application\Cli;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;

final readonly class MenuUseCase implements UseCaseInterface
{
    /** @param array<string> $categories */
    public function __construct(
        private MenuItemCollection $menuItems,
        private array $categories,
        private SyncOutputInterface $output,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Command list:');

        $grouped = [];

        foreach ($this->menuItems as $item) {
            $grouped[$item->category][$item->index] = $item;
        }

        foreach ($this->categories as $category) {
            if (empty($grouped[$category])) {
                continue;
            }

            $this->output->writeLine('- ' . $category . ':');
            ksort($grouped[$category]);

            foreach ($grouped[$category] as $index => $item) {
                $this->output->writeLine('  [' . $index . '] - ' . $item->name);
            }
        }
    }
}
