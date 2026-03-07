<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<MergeRequest> */
final class MergeRequestCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return MergeRequest::class;
    }
}
