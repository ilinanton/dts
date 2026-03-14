<?php

declare(strict_types=1);

namespace App\Application\Report;

interface DevReportPresenterInterface
{
    /** @param array<ScoredDeveloper> $scoredDevelopers */
    public function render(array $scoredDevelopers): void;
}
