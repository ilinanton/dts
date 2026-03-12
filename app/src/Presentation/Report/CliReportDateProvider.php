<?php

declare(strict_types=1);

namespace App\Presentation\Report;

use App\Application\Report\ReportDateProviderInterface;
use App\Domain\Report\ValueObject\ReportStartDate;
use DateInterval;
use DateTime;

final readonly class CliReportDateProvider implements ReportDateProviderInterface
{
    public function getReportStartDate(): ReportStartDate
    {
        $defaultDate = new DateTime();
        $defaultDate->sub(new DateInterval('P2W'));
        $input = trim(readline('Enter the start date for the report (YYYY-MM-DD): '));
        $inputDateTime = DateTime::createFromFormat('Y-m-d', $input);
        if ($inputDateTime === false) {
            echo $defaultDate->format('Y-m-d') . PHP_EOL;
            return new ReportStartDate($defaultDate->format('Y-m-d'), 'Y-m-d');
        }
        return new ReportStartDate($inputDateTime->format('Y-m-d'), 'Y-m-d');
    }
}
