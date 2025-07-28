<?php

declare(strict_types=1);

namespace App\Domain\Report\Common;

use App\Domain\Report\Metric\MetricCollection;

interface ReportInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function getMetrics(): MetricCollection;
}
