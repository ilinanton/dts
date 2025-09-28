<?php

declare(strict_types=1);

namespace App\Domain\Report\Repository;

interface DevReportDataBaseRepositoryInterface
{
    public function getData(): array;
}
