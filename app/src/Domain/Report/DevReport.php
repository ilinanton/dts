<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Report\Common\ReportInterface;
use App\Domain\Report\Metric\MetricCollection;

final readonly class DevReport implements ReportInterface
{
    public function __construct(
        private DateTime $afterAt,
    ) {
    }

    public function getName(): string
    {
        return 'Dev Report';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getMetrics(): MetricCollection
    {
        $metrics = new MetricCollection();
        return $metrics;
    }
}
