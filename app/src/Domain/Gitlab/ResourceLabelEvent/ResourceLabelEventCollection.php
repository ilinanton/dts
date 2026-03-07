<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceLabelEvent;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<ResourceLabelEvent> */
final class ResourceLabelEventCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return ResourceLabelEvent::class;
    }
}
