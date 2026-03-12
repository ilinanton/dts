<?php

declare(strict_types=1);

namespace App\Domain\Report\Repository;

use App\Domain\Report\DeveloperStatisticsCollection;
use App\Domain\Report\ReportCriteria;

interface DevReportRepositoryInterface
{
    public function getStatistics(ReportCriteria $criteria): DeveloperStatisticsCollection;
}
