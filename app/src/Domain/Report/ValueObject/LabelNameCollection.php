<?php

declare(strict_types=1);

namespace App\Domain\Report\ValueObject;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<LabelName> */
final class LabelNameCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return LabelName::class;
    }
}
