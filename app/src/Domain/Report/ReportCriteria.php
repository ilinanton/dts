<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Report\ValueObject\ReportStartDate;

final readonly class ReportCriteria
{
    /**
     * @param array<string> $testedLabelNames
     */
    public function __construct(
        public ReportStartDate $startDate,
        public array $testedLabelNames = [],
    ) {
    }
}
