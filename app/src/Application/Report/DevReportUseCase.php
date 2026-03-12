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
        $sorted = $this->sortByScore($statistics, $scores);
        $this->presenter->render($sorted, $scores);
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

    private function sortByScore(
        DeveloperStatisticsCollection $statistics,
        array &$scores,
    ): DeveloperStatisticsCollection {
        $items = iterator_to_array($statistics);
        array_multisort($scores, SORT_DESC, $items);

        $sorted = new DeveloperStatisticsCollection();
        foreach ($items as $item) {
            $sorted->add($item);
        }
        return $sorted;
    }
}
