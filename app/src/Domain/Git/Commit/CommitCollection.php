<?php

declare(strict_types=1);

namespace App\Domain\Git\Commit;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<Commit> */
final class CommitCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return Commit::class;
    }
}
