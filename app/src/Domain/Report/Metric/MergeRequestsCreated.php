<?php

declare(strict_types=1);

namespace App\Domain\Report\Metric;

use App\Domain\Report\Common\AbstractMetric;
use App\Domain\Report\Common\MetricInterface;

final readonly class MergeRequestsCreated extends AbstractMetric implements MetricInterface
{
    public function __construct(
        public int $points,
    ) {
    }

    public function getName(): string
    {
        return 'Merge Requests Created';
    }

    public function getDescription(): string
    {
        // todo write some description
        return '';
    }

    public function getType(): string
    {
        return self::TYPE_POSITIVE;
    }
}
