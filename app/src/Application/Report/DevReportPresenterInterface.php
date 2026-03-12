<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Domain\Report\DeveloperStatisticsCollection;

interface DevReportPresenterInterface
{
    /** @param array<float> $scores */
    public function render(DeveloperStatisticsCollection $statistics, array $scores): void;
}
