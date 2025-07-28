<?php

declare(strict_types=1);

namespace App\Domain\Report\Common;

abstract class AbstractMetric
{
    public const string TYPE_POSITIVE = 'POSITIVE';
    public const string TYPE_NEGATIVE = 'NEGATIVE';
}
