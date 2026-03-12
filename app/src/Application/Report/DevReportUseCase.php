<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Application\UseCaseInterface;
use App\Domain\Report\DeveloperStatisticsCollection;
use App\Domain\Report\ReportCriteria;
use App\Domain\Report\Repository\DevReportRepositoryInterface;
use App\Domain\Report\ScoringService;
use App\Domain\Report\ValueObject\LabelName;
use App\Domain\Report\ValueObject\ReportStartDate;
use DateInterval;
use DateTime;

final readonly class DevReportUseCase implements UseCaseInterface
{
    /** @param array<LabelName> $testedLabelNames */
    public function __construct(
        private DevReportRepositoryInterface $repository,
        private ScoringService $scoringService,
        private DevReportPresenterInterface $presenter,
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
        $date = $this->getDateFromUser();
        return new ReportCriteria(
            startDate: new ReportStartDate($date->format('Y-m-d'), 'Y-m-d'),
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

    private function getDateFromUser(): DateTime
    {
        $defaultDate = new DateTime();
        $defaultDate->sub(new DateInterval('P2W'));
        $input = trim(readline('Enter the start date for the report (YYYY-MM-DD): '));
        $inputDateTime = DateTime::createFromFormat('Y-m-d', $input);
        if ($inputDateTime === false) {
            echo $defaultDate->format('Y-m-d') . PHP_EOL;
            return $defaultDate;
        }
        return $inputDateTime;
    }
}
