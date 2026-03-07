<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<UseCaseInterface> */
final class UseCaseCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return UseCaseInterface::class;
    }
}
