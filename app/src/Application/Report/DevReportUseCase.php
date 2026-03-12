<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Application\UseCaseInterface;
use App\Domain\Report\DeveloperStatisticsCollection;
use App\Domain\Report\ReportCriteria;
use App\Domain\Report\Repository\DevReportRepositoryInterface;
use App\Domain\Report\ScoringService;
use App\Domain\Report\ValueObject\LabelName;

final readonly class DevReportUseCase implements UseCaseInterface
{
    /** @param array<LabelName> $testedLabelNames */
    public function __construct(
        private DevReportRepositoryInterface $repository,
        private ScoringService $scoringService,
        private DevReportPresenterInterface $presenter,
        private ReportDateProviderInterface $dateProvider,
        private array $testedLabelNames = [],
    ) {
    }

    public function execute(): void
    {
        $criteria = $this->createReportCriteria();
        $statistics = $this->repository->getStatistics($criteria);
        $scores = $this->calculateScores($statistics);
        $result = $statistics->sortByScoreDescending($scores);
        $this->presenter->render($result['collection'], $result['scores']);
    }

    private function createReportCriteria(): ReportCriteria
    {
        return new ReportCriteria(
            startDate: $this->dateProvider->getReportStartDate(),
            testedLabelNames: $this->testedLabelNames,
        );
    }

    /** @return array<float> */
    private function calculateScores(DeveloperStatisticsCollection $statistics): array
    {
        $scores = [];
        foreach ($statistics as $stat) {
            $scores[] = $this->scoringService->calculateScore($stat);
        }
        return $scores;
    }
}
