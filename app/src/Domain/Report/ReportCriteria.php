<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Report\ValueObject\ReportStartDate;

final readonly class ReportCriteria
{
    public function __construct(
        public ReportStartDate $startDate,
    ) {
    }
}
