<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<Label> */
final class LabelCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return Label::class;
    }
}
