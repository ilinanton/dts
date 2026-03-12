<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Domain\Report\ValueObject\ReportStartDate;

interface ReportDateProviderInterface
{
    public function getReportStartDate(): ReportStartDate;
}
