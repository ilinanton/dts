<?php

declare(strict_types=1);

namespace App\Domain\Report\Repository;

use App\Domain\Report\DeveloperStatistics;
use App\Domain\Report\ReportCriteria;

interface DevReportRepositoryInterface
{
    /**
     * @return DeveloperStatistics[]
     */
    public function getStatistics(ReportCriteria $criteria): array;
}
