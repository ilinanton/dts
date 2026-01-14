<?php

declare(strict_types=1);

namespace App\Application\Report;

use App\Application\UseCaseInterface;
use App\Domain\Report\ReportCriteria;
use App\Domain\Report\Repository\DevReportRepositoryInterface;
use App\Domain\Report\ScoringService;
use App\Domain\Report\ValueObject\ReportStartDate;
use App\Presentation\Report\DevReportTablePresenter;
use DateInterval;
use DateTime;

final readonly class DevReportUseCase implements UseCaseInterface
{
    public function __construct(
        private DevReportRepositoryInterface $repository,
        private ScoringService $scoringService,
        private DevReportTablePresenter $presenter,
    ) {
    }

    public function execute(): void
    {
        $criteria = $this->createReportCriteria();
        $statistics = $this->repository->getStatistics($criteria);
        $scores = $this->calculateScores($statistics);
        $this->sortByScore($statistics, $scores);
        $this->presenter->render($statistics, $scores);
    }

    private function createReportCriteria(): ReportCriteria
    {
        $date = $this->getDateFromUser();
        return new ReportCriteria(
            startDate: new ReportStartDate($date->format('Y-m-d')),
        );
    }

    private function calculateScores(array $statistics): array
    {
        $scores = [];
        foreach ($statistics as $stat) {
            $scores[] = $this->scoringService->calculateScore($stat);
        }
        return $scores;
    }

    private function sortByScore(array &$statistics, array &$scores): void
    {
        array_multisort($scores, SORT_DESC, $statistics);
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
