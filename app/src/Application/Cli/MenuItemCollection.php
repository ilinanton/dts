<?php

declare(strict_types=1);

namespace App\Application\Cli;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<MenuItem> */
final class MenuItemCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return MenuItem::class;
    }
}
