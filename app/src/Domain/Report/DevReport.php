<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Report\Common\ReportInterface;
use App\Domain\Report\Metric\MetricCollection;
use DateTime;

final readonly class DevReport implements ReportInterface
{
    public function __construct(
        public DateTime $afterAt,
        private MetricCollection $metrics,
    ) {
    }

    public function getName(): string
    {
        return 'Dev Report';
    }

    public function getDescription(): string
    {
        // todo write some description
        return '';
    }

    public function getMetrics(): MetricCollection
    {
        return $this->metrics;
    }
}
