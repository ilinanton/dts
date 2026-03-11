<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Domain\Report\DeveloperStatistics;

interface DevReportPresenterInterface
{
    /**
     * @param array<DeveloperStatistics> $statistics
     * @param array<float> $scores
     */
    public function render(array $statistics, array $scores): void;
}
