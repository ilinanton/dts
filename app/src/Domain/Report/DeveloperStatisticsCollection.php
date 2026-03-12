<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<DeveloperStatistics> */
final class DeveloperStatisticsCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return DeveloperStatistics::class;
    }
}
