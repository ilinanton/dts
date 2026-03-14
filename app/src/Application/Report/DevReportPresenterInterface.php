<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Domain\Report\ReportCriteria;

interface DevReportPresenterInterface
{
    /** @param array<ScoredDeveloper> $scoredDevelopers */
    public function render(array $scoredDevelopers, ReportCriteria $criteria): void;
}
